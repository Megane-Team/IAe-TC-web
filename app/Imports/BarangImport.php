<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BarangImport implements ToModel, WithHeadingRow
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
            // Ambil ID ruangan berdasarkan kode
            $ruangan = Ruangan::where('code', $row['ruangan'])->first();

            if (!$ruangan) {
                Log::error('Ruangan tidak ditemukan:', ['code' => $row['ruangan']]);
                $this->rowCounter++; // Tingkatkan pelacakan baris
                return null;
            }

            // Konversi status dari teks ke boolean
            $status = strtolower($row['status']) === 'dipinjam';

            // Ambil path gambar berdasarkan baris saat ini
            $photoPath = $this->extractImage($this->rowCounter);

            // Tingkatkan pelacakan baris setelah proses selesai
            $this->rowCounter++;

            return new Barang([
                'ruangan_id' => $ruangan->id,
                'name' => $row['nama_barang'],
                'code' => $row['kode_barang'],
                'status' => $status,
                'condition' => $row['kondisi'],
                'warranty' => $this->convertExcelDateToDatabaseFormat($row['garansi']),
                'photo' => $photoPath,
            ]);
        } catch (\Exception $e) {
            Log::error('Kesalahan saat mengimpor data Barang:', [
                'error' => $e->getMessage(),
                'row' => $row,
            ]);
            $this->rowCounter++; // Tetap tingkatkan pelacakan baris meskipun ada error
            return null;
        }
    }

    private function convertExcelDateToDatabaseFormat($value): ?string
    {
        try {
            // Jika dalam format Excel serial number
            if (is_numeric($value)) {
                $unixTimestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
                return date('Y-m-d', $unixTimestamp); // Format database
            }

            // Jika sudah berupa string, validasi dan ubah ke format database
            $date = \DateTime::createFromFormat('d-m-Y', $value) ?: \DateTime::createFromFormat('Y-m-d', $value);
            return $date ? $date->format('Y-m-d') : null;
        } catch (\Exception $e) {
            Log::error('Gagal mengonversi tanggal:', ['value' => $value, 'error' => $e->getMessage()]);
            return null;
        }
    }

    private function extractImage($rowNumber): ?string
    {
        try {
            foreach ($this->sheet->getDrawingCollection() as $drawing) {
                $coordinates = $drawing->getCoordinates();

                // Pastikan gambar berada pada kolom H sesuai baris
                if ($coordinates === 'G' . $rowNumber) {
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
                    $filename = 'photos/barang/' . uniqid() . '.' . $extension;

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
