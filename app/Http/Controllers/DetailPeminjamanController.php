<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman; // Pastikan Anda memiliki model DetailPeminjaman
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetailPeminjamanExport;
use Carbon\Carbon;

class DetailPeminjamanController extends Controller
{
    // Menampilkan daftar detail peminjaman
    public function index(Request $request)
    {
        $status = $request->input('status');
        $detailpeminjamans = DetailPeminjaman::when($status, function ($query, $status) {
            return $query->where('status', $status);
        })->get();

        return view('headoffice.detailpeminjaman.index', compact('detailpeminjamans'));
    }


    public function approve(DetailPeminjaman $detailpeminjaman)
    {
        $detailpeminjaman->update(['status' => 'approved']);

        return redirect()->route('detailpeminjaman.index')->with('success', 'Status berhasil diubah menjadi Approved.');
    }

    public function rejectForm(DetailPeminjaman $detailpeminjaman)
    {
        return view('headoffice.detailpeminjaman.reject', compact('detailpeminjaman'));
    }

    public function reject(Request $request, DetailPeminjaman $detailpeminjaman)
    {
        $request->validate([
            'canceledReason' => 'required|string',
        ]);

        $detailpeminjaman->update([
            'status' => 'rejected',
            'canceledReason' => $request->canceledReason,
        ]);

        return redirect()->route('detailpeminjaman.index')->with('success', 'Status berhasil diubah menjadi Rejected.');
    }

    // Mengambil detail peminjaman untuk modal
    public function show(DetailPeminjaman $detailpeminjaman)
    {
        return view('headoffice.detailpeminjaman.show', compact('detailpeminjaman'));
    }
    public function downloadPDF()
    {
        $detailpeminjamans = DetailPeminjaman::all();

        $pdf = Pdf::loadView('headoffice.detailpeminjaman.pdf', compact('detailpeminjamans'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('daftar_detailpeminjaman.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new DetailPeminjamanExport, 'daftar_detailpeminjaman.xlsx');
    }
}