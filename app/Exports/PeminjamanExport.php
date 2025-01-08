<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PeminjamanExport implements FromCollection, WithHeadings
{
    /**
     * Mengambil data yang akan diekspor.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Peminjaman::with(['barang', 'kendaraan', 'ruangan', 'user'])
            ->get()
            ->map(function ($peminjaman) {
                return [
                    'id' => $peminjaman->id,
                    'user_name' => $peminjaman->user->name ?? '',
                    'category' => $peminjaman->category,
                    'item_name' => $peminjaman->barang->code ?? $peminjaman->kendaraan->plat ?? $peminjaman->ruangan->code ?? '',
                ];
            });
    }

    /**
     * Menambahkan heading pada file export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Pengguna',
            'Kategori',
            'Nama Item',
        ];
    }
}
