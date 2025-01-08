<?php

namespace App\Imports;

use App\Models\Kendaraan;
use App\Models\Tempat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KendaraanImport implements ToModel, WithHeadingRow
{
    private $spreadsheet;
    private $sheet;

    public function __construct($file)
    {
        try {
            $reader = IOFactory::createReader('Xlsx');
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

            // Ambil tempat berdasarkan kolom parkiran
            $tempat = Tempat::where('category', 'parkiran')
                ->where('name', $row['parkiran'])
                ->first();

            if (!$tempat) {
                Log::error('Tempat parkiran tidak ditemukan:', ['name' => $row['parkiran']]);
                return null;
            }

            // Ambil path gambar kendaraan
            $photoPath = $this->extractImage($rowNumber);

            return new Kendaraan([
                'tempat_id' => $tempat->id,
                'name' => $row['nama_kendaraan'],
                'plat' => $row['plat_nomor'],
                'status' => strtolower($row['status']) === 'dipinjam',
                'condition' => $row['kondisi'],
                'warranty' => $this->convertExcelDateToDatabaseFormat($row['garansi']),
                'category' => $row['kategori'],
                'color' => $row['warna'],
                'capacity' => $row['kapasitas'],
                'photo' => $photoPath,
                'tax' => $this->convertExcelDateToDatabaseFormat($row['pajak']),
            ]);
        } catch (\Exception $e) {
            Log::error('Kesalahan saat mengimpor data Kendaraan:', [
                'error' => $e->getMessage(),
                'row' => $row,
            ]);
            return null;
        }
    }

    private function convertExcelDateToDatabaseFormat($value): ?string
    {
        try {
            if (is_numeric($value)) {
                $unixTimestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
                return date('Y-m-d', $unixTimestamp);
            }

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

                if ($coordinates === 'K' . $rowNumber) { // Asumsi gambar di kolom H
                    $imageContents = file_get_contents($drawing->getPath());

                    $extension = strtolower(pathinfo($drawing->getName(), PATHINFO_EXTENSION));
                    if (!$extension) {
                        $mimeType = mime_content_type($drawing->getPath());
                        $extension = match ($mimeType) {
                            'image/jpeg' => 'jpg',
                            'image/png' => 'png',
                            'image/gif' => 'gif',
                            default => null,
                        };

                        if (!$extension) {
                            Log::error('Ekstensi gambar tidak didukung.', ['mime_type' => $mimeType]);
                            return null;
                        }
                    }

                    $filename = 'photos/kendaraan/' . uniqid() . '.' . $extension;

                    if (Storage::disk('public')->put($filename, $imageContents)) {
                        return $filename;
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
        $rowNumber = 2;
        foreach ($this->sheet->toArray() as $index => $data) {
            if ($data[0] === $row['nama_kendaraan'] && $data[1] === $row['plat_nomor']) {
                return $index + 1;
            }
        }

        return $rowNumber;
    }
}
