<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function index()
    {
        return view('api.index');
    }

    public function checkStatus()
    {
        $detailPeminjamanData = DetailPeminjaman::where('status', 'pending')->get();

        foreach ($detailPeminjamanData as $detail) {
            $peminjamans = Peminjaman::where('detailPeminjamanId', $detail->id)->get();

            foreach ($peminjamans as $peminjaman) {
            if ($peminjaman->category == 'barang') {
                $barang = $peminjaman->barang;
                if ($barang) {
                $barang->status = true;
                $barang->save();
                }
            } elseif ($peminjaman->category == 'kendaraan') {
                $kendaraan = $peminjaman->kendaraan;
                if ($kendaraan) {
                $kendaraan->status = true;
                $kendaraan->save();
                }
            } elseif ($peminjaman->category == 'ruangan') {
                $ruangan = $peminjaman->ruangan;
                if ($ruangan) {
                $ruangan->status = true;
                $ruangan->save();
                }
            }
            }
        }

        foreach ($detailPeminjamanData as $detailPeminjaman) {
            if (Carbon::parse($detailPeminjaman->created_at)->addDays(2)->isPast()) {
                $detailPeminjaman->status = 'canceled';
                $detailPeminjaman->canceledReason = 'auto canceled after 2 days';
                $detailPeminjaman->save();
            }
        }
        return response()->json(['message' => 'Status updated successfully']);
    }
}
