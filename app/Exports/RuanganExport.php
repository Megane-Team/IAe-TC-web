<?php

namespace App\Exports;

use App\Models\Ruangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RuanganExport implements FromCollection, WithHeadings, WithDrawings, WithStyles
{
    private $ruangans;

    public function __construct()
    {
        $this->ruangans = Ruangan::with('tempat')->get();
    }

    /**
     * Mengambil data untuk diekspor.
     */
    public function collection()
    {
        return $this->ruangans->map(function ($ruangan) {
            return [
                'id' => $ruangan->id,
                'tempat' => optional($ruangan->tempat)->name,
                'code' => $ruangan->code,
                'status' => $ruangan->status ? 'Dipinjam' : 'Tidak Dipinjam',
                'capacity' => $ruangan->capacity,
                'category' => ucfirst($ruangan->category),
                'photo' => '', // Gambar akan ditambahkan melalui drawings()
                'photo_url' => $ruangan->photo ? asset('storage/' . $ruangan->photo) : 'Tidak ada foto', // URL foto
            ];
        });
    }

    /**
     * Menambahkan header kolom.
     */
    public function headings(): array
    {
        return ['ID', 'Tempat', 'Kode', 'Status', 'Kapasitas', 'Kategori', 'Photo', 'Photo URL'];
    }

    /**
     * Menyertakan gambar di kolom Photo.
     */
    public function drawings()
    {
        $drawings = [];

        foreach ($this->ruangans as $index => $ruangan) {
            if ($ruangan->photo) {
                $drawing = new Drawing();
                $drawing->setName($ruangan->code);
                $drawing->setDescription($ruangan->code);
                $drawing->setPath(public_path('storage/' . $ruangan->photo));
                $drawing->setHeight(80); // Tinggi default gambar
                $drawing->setWidth(80);  // Lebar default gambar
                $drawing->setResizeProportional(true); // Menjaga proporsi gambar
                $drawing->setCoordinates('G' . ($index + 2)); // Kolom 'G' untuk Photo
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
        $sheet->getColumnDimension('B')->setAutoSize(true); // Tempat
        $sheet->getColumnDimension('C')->setAutoSize(true); // Kode
        $sheet->getColumnDimension('D')->setAutoSize(true); // Status
        $sheet->getColumnDimension('E')->setAutoSize(true); // Kapasitas
        $sheet->getColumnDimension('F')->setAutoSize(true); // Kategori
        $sheet->getColumnDimension('G')->setWidth(15);      // Photo (gambar)
        $sheet->getColumnDimension('H')->setWidth(50);      // Photo URL

        // Menambahkan gaya pada header
        $sheet->getStyle('A1:H1')->applyFromArray([
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
        $rowCount = $this->ruangans->count() + 1; // Total baris
        $sheet->getStyle("A2:H$rowCount")->applyFromArray([
            'alignment' => [
                'horizontal' => 'center', // Semua teks di tengah horizontal
                'vertical' => 'center',   // Semua teks di tengah vertikal
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ],
        ]);

        // Mengaktifkan opsi Wrap Text pada kolom Photo URL
        $sheet->getStyle("H2:H$rowCount")->getAlignment()->setWrapText(true);

        // Menyesuaikan tinggi baris untuk gambar
        foreach (range(2, $rowCount) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(80); // Tinggi baris menyesuaikan gambar
        }

        return $sheet;
    }
}
