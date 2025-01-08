<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\Kendaraan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Exports\PeminjamanExport;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // Menampilkan daftar peminjaman
    public function index(Request $request)
    {
        $category = $request->input('category');
        $barangId = $request->input('barang_id');
        $kendaraanId = $request->input('kendaraan_id');
        $ruanganId = $request->input('ruangan_id');

        $peminjamans = Peminjaman::with(['barang', 'kendaraan', 'ruangan', 'user'])
            ->when($category, function ($query) use ($category) {
                return $query->where('category', $category);
            })
            ->when($barangId, function ($query) use ($barangId) {
                return $query->where('barang_id', $barangId);
            })
            ->when($kendaraanId, function ($query) use ($kendaraanId) {
                return $query->where('kendaraan_id', $kendaraanId);
            })
            ->when($ruanganId, function ($query) use ($ruanganId) {
                return $query->where('ruangan_id', $ruanganId);
            })
            ->get();

        $barangs = Barang::all(); // Menggunakan plural
        $kendaraans = Kendaraan::all(); // Menggunakan plural
        $ruangans = Ruangan::all(); // Menggunakan plural

        return view('headoffice.peminjaman.index', compact('peminjamans', 'barangs', 'kendaraans', 'ruangans'));
    }

    // Menyimpan peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category' => 'required|in:barang,kendaraan,ruangan',
            'barang_id' => 'nullable|exists:barang,id',
            'kendaraan_id' => 'nullable|exists:kendaraan,id',
            'ruangan_id' => 'nullable|exists:ruangan,id',
            'tanggal_peminjaman' => 'required|date',
        ]);

        Peminjaman::create($request->all());

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    // Menampilkan detail peminjaman
    public function show(Peminjaman $peminjaman)
    {
        return view('headoffice.peminjaman.show', compact('peminjaman'));
    }

    // Menampilkan form untuk mengedit peminjaman
    public function edit(Peminjaman $peminjaman)
    {
        $barang = Barang::all();
        $kendaraan = Kendaraan::all();
        $ruangan = Ruangan::all();

        return view('headoffice.peminjaman.edit', compact('peminjaman', 'barang', 'kendaraan', 'ruangan'));
    }

    // Mengupdate peminjaman yang ada
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category' => 'required|in:barang,kendaraan,ruangan',
            'barang_id' => 'nullable|exists:barang,id',
            'kendaraan_id' => 'nullable|exists:kendaraan,id',
            'ruangan_id' => 'nullable|exists:ruangan,id',
            'tanggal_peminjaman' => 'required|date',
        ]);

        $peminjaman->update($request->all());

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function downloadPDF()
    {
        $peminjamans = Peminjaman::all();

        $pdf = Pdf::loadView('headoffice.peminjaman.pdf', compact('peminjamans'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('daftar_peminjaman.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PeminjamanExport, 'daftar_peminjaman.xlsx');
    }

}
