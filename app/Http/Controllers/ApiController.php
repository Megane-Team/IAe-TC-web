<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    private $detailPeminjaman;

    public function index()
    {
        return response()->json([
            'message' => 'Welcome to the API',
            'status' => 'Connected'
        ]);
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

    public function storeDetailPeminjaman() 
    {
        $data = request()->validate([
            'status' => 'required',
            'borrowedDate' => 'required',
            'estimatedTime' => 'required',
            'returnDate' => 'nullable',
            'objective' => 'required', 
            'destination' => 'nullable',
            'passenger' => 'nullable',
            'canceledReason' => 'nullable',
            'user_id' => 'required',
        ]);

        $data['borrowedDate'] = Carbon::parse($data['borrowedDate'])->format('Y-m-d H:i:s');
        $data['estimatedTime'] = Carbon::parse($data['estimatedTime'])->format('Y-m-d H:i:s');
        if (isset($data['returnDate'])) {
            $data['returnDate'] = Carbon::parse($data['returnDate'])->format('Y-m-d H:i:s');
        }

        $user = User::where('nik', $data['user_id'])->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $data['user_id'] = $user->id;

        $detailPeminjaman = DetailPeminjaman::create($data);

        return response()->json(['message' => 'Detail Peminjaman created successfully', 'data' => $detailPeminjaman], 200);
    }

    public function storePeminjaman()
    {
        $data = request()->validate([
            'category' => 'required',
            'detailPeminjamanId' => 'nullable',
            'barang_id' => 'nullable',
            'kendaraan_id' => 'nullable',
            'ruangan_id' => 'nullable',
            'user_id' => 'required',
        ]);

        $detailPeminjaman = DetailPeminjaman::latest()->first();

        if (!$detailPeminjaman) {
            return response()->json(['message' => 'Detail Peminjaman not found'], 404);
        }

        $user = User::where('nik', $data['user_id'])->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        $data['user_id'] = $user->id;

        $data['detailPeminjamanId'] = $detailPeminjaman->id;

        $peminjaman = Peminjaman::create($data);

        return response()->json(['message' => 'Peminjaman created successfully', 'data' => $peminjaman], 200);
    }
}
