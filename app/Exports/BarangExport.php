<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangExport implements FromCollection, WithHeadings, WithDrawings, WithStyles
{
    private $barangs;

    public function __construct()
    {
        $this->barangs = Barang::with('ruangan')->get();
    }

    /**
     * Mengambil data untuk diekspor.
     */
    public function collection()
    {
        return $this->barangs->map(function ($barang) {
            return [
                'id' => $barang->id,
                'name' => $barang->name,
                'code' => $barang->code,
                'status' => $barang->status ? 'Dipinjam' : 'Tidak Dipinjam',
                'condition' => ucfirst($barang->condition),
                'warranty' => $barang->warranty,
                'ruangan' => optional($barang->ruangan)->code,
                'photo' => '', // Gambar akan ditambahkan melalui drawings()
                'photo_url' => $barang->photo ? asset('storage/' . $barang->photo) : 'Tidak ada foto', // URL foto
            ];
        });
    }

    /**
     * Menambahkan header kolom.
     */
    public function headings(): array
    {
        return [
            'ID', 'Nama Barang', 'Kode Barang', 
            'Status', 'Kondisi', 'Garansi', 'Ruangan', 'Photo', 'Photo URL'
        ];
    }

    /**
     * Menyertakan gambar di kolom Photo.
     */
    public function drawings()
    {
        $drawings = [];

        foreach ($this->barangs as $index => $barang) {
            if ($barang->photo) {
                $drawing = new Drawing();
                $drawing->setName($barang->name);
                $drawing->setDescription($barang->name);
                $drawing->setPath(public_path('storage/' . $barang->photo));
                $drawing->setHeight(80);
                $drawing->setWidth(80);
                $drawing->setResizeProportional(true);
                $drawing->setCoordinates('H' . ($index + 2)); // Kolom 'H' untuk Photo
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
        $sheet->getColumnDimension('B')->setAutoSize(true); // Nama Barang
        $sheet->getColumnDimension('C')->setAutoSize(true); // Kode Barang
        $sheet->getColumnDimension('D')->setAutoSize(true); // Status
        $sheet->getColumnDimension('E')->setAutoSize(true); // Kondisi
        $sheet->getColumnDimension('F')->setAutoSize(true); // Garansi
        $sheet->getColumnDimension('G')->setAutoSize(true); // Ruangan
        $sheet->getColumnDimension('H')->setWidth(15);      // Photo
        $sheet->getColumnDimension('I')->setWidth(30);      // Photo URL (disesuaikan)

        // Menambahkan gaya pada header
        $sheet->getStyle('A1:I1')->applyFromArray([
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
        $rowCount = $this->barangs->count() + 1;
        $sheet->getStyle("A2:I$rowCount")->applyFromArray([
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
        $sheet->getStyle("I2:I$rowCount")->getAlignment()->setWrapText(true);

        // Menyesuaikan tinggi baris untuk gambar
        foreach (range(2, $rowCount) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(80);
        }

        return $sheet;
    }
}
