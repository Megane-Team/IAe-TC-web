<?php

namespace App\Exports;

use App\Models\DetailPeminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DetailPeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Get all data from the DetailPeminjaman table.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DetailPeminjaman::with('user')->get();
    }

    /**
     * Map data for each row in the exported file.
     *
     * @param mixed $detailPeminjaman
     * @return array
     */
    public function map($detailPeminjaman): array
    {
        return [
            $detailPeminjaman->id,
            ucfirst($detailPeminjaman->status),
            $detailPeminjaman->borrowedDate ? $detailPeminjaman->borrowedDate->format('d-m-Y') : '-',
            $detailPeminjaman->estimatedTime ? $detailPeminjaman->estimatedTime->format('d-m-Y') : '-',
            $detailPeminjaman->returnDate ? $detailPeminjaman->returnDate->format('d-m-Y') : '-',
            $detailPeminjaman->objective,
            $detailPeminjaman->destination ?? '-',
            $detailPeminjaman->passenger ?? '-',
            $detailPeminjaman->canceledReason ?? '-',
            $detailPeminjaman->user->name ?? 'Tidak Diketahui',
            $detailPeminjaman->created_at->format('d-m-Y H:i:s'),
            $detailPeminjaman->updated_at->format('d-m-Y H:i:s'),
        ];
    }

    /**
     * Define headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Status',
            'Tanggal Dipinjam',
            'Estimasi Waktu',
            'Tanggal Kembali',
            'Tujuan',
            'Destinasi',
            'Jumlah Penumpang',
            'Alasan Pembatalan',
            'Nama Pengguna',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }
}
