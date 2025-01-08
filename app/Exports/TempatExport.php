<?php

namespace App\Exports;

use App\Models\Tempat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TempatExport implements FromCollection, WithHeadings, WithDrawings, WithStyles
{
    private $tempats;

    public function __construct()
    {
        $this->tempats = Tempat::select('name', 'category', 'photo')->get();
    }

    /**
     * Mengambil data untuk diekspor.
     */
    public function collection()
    {
        return $this->tempats->map(function ($item) {
            return [
                'name' => $item->name,
                'category' => $item->category,
                'photo' => '', // Kosongkan data Photo untuk diisi gambar
                'photo_url' => $item->photo ? asset('storage/' . $item->photo) : null, // Link di kolom Photo URL
            ];
        });
    }

    /**
     * Menambahkan header kolom.
     */
    public function headings(): array
    {
        return ['Name', 'Category', 'Photo', 'Photo URL'];
    }

    /**
     * Menyertakan gambar di kolom Photo.
     */
    public function drawings()
    {
        $drawings = [];

        foreach ($this->tempats as $index => $tempat) {
            if ($tempat->photo) {
                $drawing = new Drawing();
                $drawing->setName($tempat->name);
                $drawing->setDescription($tempat->name);
                $drawing->setPath(public_path('storage/' . $tempat->photo));
                $drawing->setHeight(80); // Tinggi default gambar
                $drawing->setWidth(80);  // Lebar default gambar
                $drawing->setResizeProportional(true); // Menjaga proporsi gambar
                $drawing->setCoordinates('C' . ($index + 2)); // Kolom 'C' untuk Photo
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }

    /**
     * Menambahkan gaya pada tabel.
     */
    public function styles(Worksheet $sheet)
    {
        // Menyesuaikan lebar kolom
        $sheet->getColumnDimension('A')->setAutoSize(true); // Name
        $sheet->getColumnDimension('B')->setAutoSize(true); // Category
        $sheet->getColumnDimension('C')->setWidth(15);      // Photo (gambar)
        $sheet->getColumnDimension('D')->setAutoSize(true); // Photo URL

        // Menambahkan gaya pada header
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ],
        ]);

        // Menambahkan batas pada data
        $rowCount = $this->tempats->count() + 1; // Total baris
        $sheet->getStyle("A1:D$rowCount")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ],
            'alignment' => [
                'horizontal' => 'center', // Semua teks di tengah
                'vertical' => 'center',
            ],
        ]);

        // Menyesuaikan tinggi baris untuk gambar
        foreach (range(2, $rowCount) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(80); // Tinggi baris menyesuaikan gambar
        }

        return $sheet;
    }
}
