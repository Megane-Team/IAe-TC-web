<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromCollection, WithHeadings, WithDrawings, WithStyles
{
    private $users;

    public function __construct()
    {
        $this->users = User::all();
    }

    /**
     * Mengambil data untuk diekspor.
     */
    public function collection()
    {
        return $this->users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => ucfirst($user->role),
                'unit' => $user->unit,
                'address' => $user->address,
                'phoneNumber' => $user->phoneNumber,
                'photo' => '', // Gambar akan ditambahkan melalui drawings()
                'photo_url' => $user->photo ? asset('storage/' . $user->photo) : 'Tidak ada foto', // URL foto
            ];
        });
    }

    /**
     * Menambahkan header kolom.
     */
    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Role', 'Unit', 'Address', 'Phone Number', 'Photo', 'Photo URL'];
    }

    /**
     * Menyertakan gambar di kolom Photo.
     */
    public function drawings()
    {
        $drawings = [];

        foreach ($this->users as $index => $user) {
            if ($user->photo) {
                $drawing = new Drawing();
                $drawing->setName($user->name);
                $drawing->setDescription($user->name);
                $drawing->setPath(public_path('storage/' . $user->photo));
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
        $sheet->getColumnDimension('B')->setAutoSize(true); // Name
        $sheet->getColumnDimension('C')->setAutoSize(true); // Email
        $sheet->getColumnDimension('D')->setAutoSize(true); // Role
        $sheet->getColumnDimension('E')->setAutoSize(true); // Unit
        $sheet->getColumnDimension('F')->setAutoSize(true); // Address
        $sheet->getColumnDimension('G')->setAutoSize(true); // Phone Number
        $sheet->getColumnDimension('H')->setWidth(15);      // Photo
        $sheet->getColumnDimension('I')->setWidth(50);      // Photo URL

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
        $rowCount = $this->users->count() + 1;
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
