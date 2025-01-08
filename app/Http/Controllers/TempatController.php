<?php

namespace App\Http\Controllers;

use App\Models\Tempat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TempatExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TempatImport;


class TempatController extends Controller
{
    // Menampilkan daftar semua tempat
    public function index(Request $request)
    {
        $category = $request->input('category');
        $tempats = Tempat::when($category, function ($query, $category) {
            return $query->where('category', $category);
        })->get();

        return view('admin.tempat.index', compact('tempats'));
    }


    // Menampilkan tempat dengan kategori parkiran
    public function parkiran()
    {
        $tempats = Tempat::where('category', 'parkiran')->get();
        return view('admin.tempat.parkiran', compact('tempats')); // Pastikan file parkiran.blade.php ada
    }

    // Menampilkan tempat dengan kategori gedung
    public function gedung()
    {
        $tempats = Tempat::where('category', 'gedung')->get();
        return view('admin.tempat.gedung', compact('tempats')); // Pastikan file gedung.blade.php ada
    }

    // Menampilkan form untuk membuat tempat baru
    public function create()
    {
        return view('admin.tempat.create'); // Pastikan file create.blade.php ada
    }

    // Menyimpan tempat baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tempats',
            'category' => 'required|in:gedung,parkiran',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos/tempat', 'public');
        }

        Tempat::create($data);
        return redirect()->route('tempat.index')->with('success', 'Tempat berhasil ditambahkan.');
    }

    // Menampilkan detail tempat
    public function show(Tempat $tempat)
    {
        return view('admin.tempat.show', compact('tempat')); // Pastikan file show.blade.php ada
    }

    // Menampilkan form untuk mengedit tempat
    public function edit(Tempat $tempat)
    {
        return view('admin.tempat.edit', compact('tempat')); // Pastikan file edit.blade.php ada
    }

    // Mengupdate tempat yang ada
    public function update(Request $request, Tempat $tempat)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tempats,name,' . $tempat->id,
            'category' => 'required|in:gedung,parkiran',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Hapus foto lama jika diminta
        if ($request->has('remove_photo') && $tempat->photo) {
            Storage::disk('public')->delete($tempat->photo);
            $data['photo'] = null;
        }

        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            if ($tempat->photo) {
                Storage::disk('public')->delete($tempat->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos/tempat', 'public');
        }

        $tempat->update($data);

        return redirect()->route('tempat.index')->with('success', 'Tempat berhasil diperbarui.');
    }
    public function destroy(Tempat $tempat)
    {
        // Hapus foto dari penyimpanan jika ada
        if ($tempat->photo) {
            Log::info('Menghapus foto tempat dengan path: ' . $tempat->photo);
            Storage::disk('public')->delete($tempat->photo);
        }

        // Hapus data tempat
        $tempat->delete();

        return redirect()->route('tempat.index')->with('success', 'Tempat berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        Tempat::whereIn('id', $ids)->delete();
        return response()->json(['success' => true]);
    }

    public function downloadPDF()
    {
        $tempats = Tempat::all(); // Data untuk PDF

        $pdf = Pdf::loadView('admin.tempat.pdf', compact('tempats'))
            ->setPaper('a4', 'potrait');

        return $pdf->download('daftar_tempat.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new TempatExport, 'daftar_tempat.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new TempatImport($request->file('file')), $request->file('file'));
            return redirect()->route('tempat.index')->with('success', 'Data berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat mengimpor data Tempat:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal mengimpor data. Silakan periksa format file.');
        }
    }


    public function showImportForm()
    {
        return view('admin.tempat.import');
    }

    public function downloadSample()
    {
        $filePath = public_path('samples/sample_tempat.xlsx');

        // Pastikan file benar-benar ada sebelum mencoba mengunduh
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath, 'Contoh_Data_Tempat.xlsx');
    }
}
