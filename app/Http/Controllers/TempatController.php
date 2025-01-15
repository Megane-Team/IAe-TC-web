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
use Illuminate\Support\Facades\Http;


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

        // make a post request to the api
        $apiUrl = config('app.api_url');

        $apiToken = session('api_token');

        $response = Http::asMultipart()->withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->post($apiUrl . '/tempats', [
            [
                'name'     => 'name',
                'contents' => $request->input('name')
            ],
            [
                'name'     => 'category',
                'contents' => $request->input('category')
            ],
            [
                'name'     => 'photo',
                'contents' => fopen($request->file('photo')->getPathname(), 'r'),
                'filename' => $request->file('photo')->getClientOriginalName()
            ]
        ]);

        if ($response->successful()) {
            // Handle photo upload
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('photos/tempat', 'public');
            }

            Tempat::create($data);
            return redirect()->route('tempat.index')->with('success', 'Tempat berhasil ditambahkan.');
        } else {

            return redirect()->back()->with('error', 'Gagal menambahkan tempat. Silakan coba lagi.');
        }
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

        // make a put request to the api
        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::asMultipart()->withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->put($apiUrl . '/tempats/' . $tempat->name, [
            [
                'name'     => 'name',
                'contents' => $request->input('name')
            ],
            [
                'name'     => 'category',
                'contents' => $request->input('category')
            ],
            [
                'name'     => 'photo',
                'contents' => $request->hasFile('photo') ? fopen($request->file('photo')->getPathname(), 'r') : null,
                'filename' => $request->hasFile('photo') ? $request->file('photo')->getClientOriginalName() : null
            ]
        ]);

        if ($response->successful()) {
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
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui tempat. Silakan coba lagi.');
        }
    }
    public function destroy(Tempat $tempat)
    {
        // make a delete request to the api
        $apiUrl = config('app.api_url');

        $apiToken = session('api_token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->delete($apiUrl . '/tempats/' . $tempat->name);

        if ($response->successful()) {
            if ($tempat->photo) {
                Storage::disk('public')->delete($tempat->photo);
            }
    
            // Hapus data tempat
            $tempat->delete();
    
            return redirect()->route('tempat.index')->with('success', 'Tempat berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus tempat. Silakan coba lagi.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        $tempats = Tempat::whereIn('id', $ids)->get();
        $names = $tempats->pluck('name')->toArray();

        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->delete($apiUrl . '/tempats/bulk', [
            'names' => $names
        ]);

        if ($response->successful()) {
            foreach ($tempats as $tempat) {
            if ($tempat->photo) {
                Storage::disk('public')->delete($tempat->photo);
            }
            $tempat->delete();
            }
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus tempat secara bulk.']);
        }
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
