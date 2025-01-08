<?php

namespace App\Exports;

use App\Models\Kendaraan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KendaraanExport implements FromCollection, WithHeadings, WithDrawings, WithStyles
{
    private $kendaraans;

    public function __construct()
    {
        $this->kendaraans = Kendaraan::with('tempat')->get();
    }

    /**
     * Mengambil data untuk diekspor.
     */
    public function collection()
    {
        return $this->kendaraans->map(function ($kendaraan) {
            return [
                'id' => $kendaraan->id,
                'name' => $kendaraan->name,
                'plat' => $kendaraan->plat,
                'status' => $kendaraan->status ? 'Dipinjam' : 'Tidak Dipinjam',
                'condition' => ucfirst($kendaraan->condition),
                'warranty' => $kendaraan->warranty,
                'capacity' => $kendaraan->capacity,
                'category' => ucfirst($kendaraan->category),
                'color' => $kendaraan->color,
                'tax' => $kendaraan->tax,
                'tempat' => optional($kendaraan->tempat)->name,
                'photo' => '', // Gambar akan ditambahkan melalui drawings()
                'photo_url' => $kendaraan->photo ? asset('storage/' . $kendaraan->photo) : 'Tidak ada foto',
            ];
        });
    }

    /**
     * Menambahkan header kolom.
     */
    public function headings(): array
    {
        return [
            'ID', 'Nama Kendaraan', 'Plat', 'Status', 'Kondisi',
            'Garansi', 'Kapasitas', 'Kategori', 'Warna', 'Pajak',
            'Tempat', 'Photo', 'Photo URL'
        ];
    }

    /**
     * Menyertakan gambar di kolom Photo.
     */
    public function drawings()
    {
        $drawings = [];

        foreach ($this->kendaraans as $index => $kendaraan) {
            if ($kendaraan->photo) {
                $drawing = new Drawing();
                $drawing->setName($kendaraan->name);
                $drawing->setDescription($kendaraan->name);
                $drawing->setPath(public_path('storage/' . $kendaraan->photo));
                $drawing->setHeight(80); // Tinggi default gambar
                $drawing->setWidth(80);  // Lebar default gambar
                $drawing->setResizeProportional(true); // Menjaga proporsi gambar
                $drawing->setCoordinates('L' . ($index + 2)); // Kolom 'L' untuk Photo
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
        $sheet->getColumnDimension('A')->setAutoSize(true); // ID
        $sheet->getColumnDimension('B')->setAutoSize(true); // Nama Kendaraan
        $sheet->getColumnDimension('C')->setAutoSize(true); // Plat
        $sheet->getColumnDimension('D')->setAutoSize(true); // Status
        $sheet->getColumnDimension('E')->setAutoSize(true); // Kondisi
        $sheet->getColumnDimension('F')->setAutoSize(true); // Garansi
        $sheet->getColumnDimension('G')->setAutoSize(true); // Kapasitas
        $sheet->getColumnDimension('H')->setAutoSize(true); // Kategori
        $sheet->getColumnDimension('I')->setAutoSize(true); // Warna
        $sheet->getColumnDimension('J')->setAutoSize(true); // Pajak
        $sheet->getColumnDimension('K')->setAutoSize(true); // Tempat
        $sheet->getColumnDimension('L')->setWidth(15);      // Photo
        $sheet->getColumnDimension('M')->setWidth(50);      // Photo URL

        // Menambahkan gaya pada header
        $sheet->getStyle('A1:M1')->applyFromArray([
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

        // Menambahkan gaya pada seluruh data
        $rowCount = $this->kendaraans->count() + 1;
        $sheet->getStyle("A2:M$rowCount")->applyFromArray([
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

        // Mengaktifkan opsi Wrap Text pada kolom Photo URL
        $sheet->getStyle("M2:M$rowCount")->getAlignment()->setWrapText(true);

        // Menyesuaikan tinggi baris untuk gambar
        foreach (range(2, $rowCount) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(80); // Tinggi baris menyesuaikan gambar
        }

        return $sheet;
    }
}
