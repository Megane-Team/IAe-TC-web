<?php

namespace App\Imports;

use App\Models\Ruangan;
use App\Models\Tempat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RuanganImport implements ToModel, WithHeadingRow
{
    private $spreadsheet;
    private $sheet;
    private $rowCounter; // Variabel pelacak baris data saat ini

    public function __construct($file)
    {
        try {
            $reader = IOFactory::createReader('Xlsx');
            $this->spreadsheet = $reader->load($file->getRealPath());
            $this->sheet = $this->spreadsheet->getActiveSheet();
            $this->rowCounter = 2; // Mulai dari baris kedua (karena baris pertama adalah heading)
        } catch (\Exception $e) {
            Log::error('Gagal memuat file Excel:', ['error' => $e->getMessage()]);
        }
    }

    public function model(array $row)
    {
        try {
            // Ambil ID tempat berdasarkan kategori gedung dan nama gedung
            $tempat = Tempat::where('category', 'gedung')
                ->where('name', $row['gedung'])
                ->first();

            if (!$tempat) {
                Log::error('Gedung tidak ditemukan:', ['name' => $row['gedung']]);
                $this->rowCounter++; // Tingkatkan pelacakan baris
                return null;
            }

            // Konversi status dari teks ke boolean
            $status = strtolower($row['status']) === 'dipinjam';

            // Ambil path gambar berdasarkan baris saat ini
            $photoPath = $this->extractImage($this->rowCounter);

            // Tingkatkan pelacakan baris setelah proses selesai
            $this->rowCounter++;

            return new Ruangan([
                'tempat_id' => $tempat->id,
                'code' => $row['kode_ruangan'],
                'status' => $status,
                'capacity' => $row['kapasitas'],
                'category' => $row['kategori'],
                'photo' => $photoPath,
            ]);
        } catch (\Exception $e) {
            Log::error('Kesalahan saat mengimpor data Ruangan:', [
                'error' => $e->getMessage(),
                'row' => $row,
            ]);
            $this->rowCounter++; // Tetap tingkatkan pelacakan baris meskipun ada error
            return null;
        }
    }

    private function extractImage($rowNumber): ?string
    {
        try {
            foreach ($this->sheet->getDrawingCollection() as $drawing) {
                $coordinates = $drawing->getCoordinates();

                // Pastikan gambar berada pada kolom F sesuai baris
                if ($coordinates === 'F' . $rowNumber) {
                    $imageContents = file_get_contents($drawing->getPath());

                    // Dapatkan ekstensi gambar
                    $extension = strtolower(pathinfo($drawing->getName(), PATHINFO_EXTENSION));

                    // Jika ekstensi kosong, gunakan tipe MIME
                    if (!$extension) {
                        $mimeType = mime_content_type($drawing->getPath());
                        $extension = match ($mimeType) {
                            'image/jpeg' => 'jpg',
                            'image/png' => 'png',
                            'image/gif' => 'gif',
                            default => null,
                        };

                        if (!$extension) {
                            Log::error('Ekstensi gambar tidak didukung berdasarkan MIME.', [
                                'mime_type' => $mimeType,
                            ]);
                            return null;
                        }
                    }

                    // Validasi ekstensi gambar
                    if (!in_array($extension, ['jpeg', 'png', 'jpg', 'gif'])) {
                        Log::error('Ekstensi gambar tidak didukung.', ['extension' => $extension]);
                        return null;
                    }

                    // Tentukan nama file unik dan path
                    $filename = 'photos/ruangan/' . uniqid() . '.' . $extension;

                    // Simpan file ke storage
                    if (Storage::disk('public')->put($filename, $imageContents)) {
                        return $filename; // Jalur relatif untuk disimpan di database
                    } else {
                        Log::error('Gagal menyimpan gambar ke storage.', ['filename' => $filename]);
                        return null;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengekstrak gambar:', ['error' => $e->getMessage()]);
        }

        return null;
    }
}