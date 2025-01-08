<?php

namespace App\Imports;

use App\Models\Tempat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TempatImport implements ToModel, WithHeadingRow
{
    private $spreadsheet;
    private $sheet;

    public function __construct($file)
    {
        try {
            $reader = IOFactory::createReader('Xlsx'); // Pastikan format file sesuai
            $this->spreadsheet = $reader->load($file->getRealPath());
            $this->sheet = $this->spreadsheet->getActiveSheet();
        } catch (\Exception $e) {
            Log::error('Gagal memuat file Excel:', ['error' => $e->getMessage()]);
        }
    }

    public function model(array $row)
    {
        try {
            $rowNumber = $this->getRowNumber($row);

            // Ambil path gambar
            $photoPath = $this->extractImage($rowNumber);

            return new Tempat([
                'name' => $row['name'],
                'category' => $row['category'],
                'photo' => $photoPath,
            ]);
        } catch (\Exception $e) {
            Log::error('Kesalahan saat mengimpor data Tempat:', [
                'error' => $e->getMessage(),
                'row' => $row,
            ]);
            return null;
        }
    }

    private function extractImage($rowNumber): ?string
    {
        try {
            foreach ($this->sheet->getDrawingCollection() as $drawing) {
                $coordinates = $drawing->getCoordinates();

                // Pastikan gambar berada pada kolom yang sesuai
                if ($coordinates === 'C' . $rowNumber) { // Asumsi gambar di kolom C
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
                    $filename = 'photos/tempat/' . uniqid() . '.' . $extension;

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


    private function getRowNumber(array $row): int
    {
        $rowNumber = 2; // Asumsi data mulai dari baris kedua
        foreach ($this->sheet->toArray() as $index => $data) {
            if ($data[0] === $row['name'] && $data[1] === $row['category']) {
                return $index + 1;
            }
        }

        return $rowNumber;
    }
}
