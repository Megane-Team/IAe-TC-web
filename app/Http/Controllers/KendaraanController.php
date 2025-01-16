<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Tempat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KendaraanExport; // Pastikan Anda memiliki kelas KendaraanExport
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KendaraanImport; // Pastikan Anda memiliki kelas KendaraanImport
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;


class KendaraanController extends Controller
{
    // Menampilkan daftar kendaraan berdasarkan filter
    public function index(Request $request)
    {
        $status = $request->input('status');
        $condition = $request->input('condition');
        $tempatId = $request->input('tempat_id');

        // Ambil kendaraan berdasarkan filter
        $kendaraans = Kendaraan::with('tempat')
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($condition, function ($query) use ($condition) {
                return $query->where('condition', $condition);
            })
            ->when($tempatId, function ($query) use ($tempatId) {
                return $query->where('tempat_id', $tempatId);
            })
            ->get();

        // Ambil tempat kategori parkiran untuk dropdown
        $tempats = Tempat::where('category', 'parkiran')->get();

        return view('admin.kendaraan.index', compact('kendaraans', 'tempats'));
    }

    // Menampilkan form untuk menambah kendaraan
    public function create()
    {
        $tempats = Tempat::where('category', 'parkiran')->get(); // Ambil tempat kategori parkiran
        return view('admin.kendaraan.create', compact('tempats'));
    }

    // Menyimpan kendaraan baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'plat' => 'required|string|max:50|unique:kendaraans',
            'status' => 'required|boolean',
            'condition' => 'required|in:bagus,kurang_bagus,rusak',
            'warranty' => 'required|date_format:d-m-Y',
            'capacity' => 'nullable|integer',
            'category' => 'required|in:mobil,motor,truk',
            'color' => 'required|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tempat_id' => 'required|exists:tempats,id',
            'tax' => 'nullable|date_format:d-m-Y',
        ]);

        $data = $request->all();
        $data['warranty'] = Carbon::createFromFormat('d-m-Y', $request->warranty)->format('Y-m-d');
        $data['tax'] = $request->tax ? Carbon::createFromFormat('d-m-Y', $request->tax)->format('Y-m-d') : null;

        // make a post request to the api
        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::asMultipart()->withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->post($apiUrl . '/kendaraans', [
            [
            'name'     => 'name',
            'contents' => $request->input('name')
            ],
            [
            'name'     => 'plat',
            'contents' => $request->input('plat')
            ],
            [
            'name'     => 'status',
            'contents' => $request->input('status')
            ],
            [
            'name'     => 'condition',
            'contents' => $request->input('condition')
            ],
            [
            'name'     => 'warranty',
            'contents' => $request->input('warranty')
            ],
            [
            'name'     => 'capacity',
            'contents' => $request->input('capacity')
            ],
            [
            'name'     => 'category',
            'contents' => $request->input('category')
            ],
            [
            'name'     => 'color',
            'contents' => $request->input('color')
            ],
            [
            'name'     => 'tempat_name',
            'contents' => Tempat::find($request->input('tempat_id'))->name
            ],
            [
            'name'     => 'tax',
            'contents' => $request->input('tax')
            ],
            [
            'name'     => 'photo',
            'contents' => $request->hasFile('photo') ? fopen($request->file('photo')->getPathname(), 'r') : null,
            'filename' => $request->hasFile('photo') ? $request->file('photo')->getClientOriginalName() : null
            ]
        ]);

        if ($response->successful()) {
            // Handle photo upload
            if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos/kendaraan', 'public');
            }

            Kendaraan::create($data);
            return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan kendaraan. Silakan coba lagi.');
        }
    }

    // Menampilkan form untuk mengedit kendaraan
    public function edit(Kendaraan $kendaraan)
    {
        $tempats = Tempat::where('category', 'parkiran')->get(); // Ambil tempat kategori parkiran
        return view('admin.kendaraan.edit', compact('kendaraan', 'tempats'));
    }

    // Memperbarui kendaraan
    public function update(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'plat' => 'required|string|max:50|unique:kendaraans,plat,' . $kendaraan->id,
            'status' => 'required|boolean',
            'condition' => 'required|in:bagus,kurang_bagus,rusak',
            'warranty' => 'required|date_format:d-m-Y',
            'capacity' => 'nullable|integer',
            'category' => 'required|in:mobil,motor,truk',
            'color' => 'required|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'nullable|boolean',
            'tempat_id' => 'required|exists:tempats,id',
            'tax' => 'nullable|date_format:d-m-Y',
        ]);

        $data = $request->all();
        $data['warranty'] = Carbon::createFromFormat('d-m-Y', $request->warranty)->format('Y-m-d');
        $data['tax'] = $request->tax ? Carbon::createFromFormat('d-m-Y', $request->tax)->format('Y-m-d') : null;

        // make a put request to the api
        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::asMultipart()->withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->put($apiUrl . '/kendaraans/' . $kendaraan->plat, [
            [
            'name'     => 'name',
            'contents' => $request->input('name')
            ],
            [
            'name'     => 'plat',
            'contents' => $request->input('plat')
            ],
            [
            'name'     => 'status',
            'contents' => $request->input('status')
            ],
            [
            'name'     => 'condition',
            'contents' => $request->input('condition')
            ],
            [
            'name'     => 'warranty',
            'contents' => $request->input('warranty')
            ],
            [
            'name'     => 'capacity',
            'contents' => $request->input('capacity')
            ],
            [
            'name'     => 'category',
            'contents' => $request->input('category')
            ],
            [
            'name'     => 'color',
            'contents' => $request->input('color')
            ],
            [
            'name'     => 'tempat_name',
            'contents' => Tempat::find($request->input('tempat_id'))->name
            ],
            [
            'name'     => 'tax',
            'contents' => $request->input('tax')
            ],
            [
            'name'     => 'photo',
            'contents' => $request->hasFile('photo') ? fopen($request->file('photo')->getPathname(), 'r') : null,
            'filename' => $request->hasFile('photo') ? $request->file('photo')->getClientOriginalName() : null
            ]
        ]);

        if ($response->successful()) {
            // Hapus foto lama jika diminta
            if ($request->has('remove_photo') && $kendaraan->photo) {
            Storage::disk('public')->delete($kendaraan->photo);
            $data['photo'] = null;
            }

            // Upload foto baru jika ada
            if ($request->hasFile('photo')) {
            if ($kendaraan->photo) {
            Storage::disk('public')->delete($kendaraan->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos/kendaraan', 'public');
            }

            $kendaraan->update($data);

            return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui kendaraan. Silakan coba lagi.');
        }
    }

    // Menghapus kendaraan
    public function destroy(Kendaraan $kendaraan)
    {
        // make a delete request to the api
        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->delete($apiUrl . '/kendaraans/' . $kendaraan->plat);

        if ($response->successful()) {
            if ($kendaraan->photo) {
            Storage::disk('public')->delete($kendaraan->photo);
            }

            $kendaraan->delete();
            return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus kendaraan. Silakan coba lagi.');
        }
    }

    // Mengambil detail kendaraan
    public function show(Kendaraan $kendaraan)
    {
        $qrCode = QrCode::size(200)->generate("http://iae-tc.app/kendaraan/{$kendaraan->id}");
        return view('admin.kendaraan.show', compact('kendaraan','qrCode'));
    }

    // Menghapus beberapa kendaraan sekaligus
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        $kendaraans = Kendaraan::whereIn('id', $ids)->get();
        $plats = $kendaraans->pluck('plat')->toArray();

        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->delete($apiUrl . '/kendaraans/bulk', [
            'plats' => $plats
        ]);

        if ($response->successful()) {
            foreach ($kendaraans as $kendaraan) {
            if ($kendaraan->photo) {
                Storage::disk('public')->delete($kendaraan->photo);
            }
            $kendaraan->delete();
            }
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus kendaraan secara bulk.']);
        }
    }

    public function downloadPDF()
    {
        $kendaraans = Kendaraan::all(); // Data untuk PDF

        $pdf = Pdf::loadView('admin.kendaraan.pdf', compact('kendaraans'))
            ->setPaper('a4', 'potrait');

        return $pdf->download('daftar_kendaraan.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new KendaraanExport, 'daftar_kendaraan.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::asMultipart()->withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->post($apiUrl . '/kendaraans/import', [
            [
            'name'     => 'file',
            'contents' => fopen($request->file('file')->getPathname(), 'r'),
            'filename' => $request->file('file')->getClientOriginalName()
            ]
        ]);

        if ($response->successful()) {
            try {
            Excel::import(new KendaraanImport($request->file('file')), $request->file('file'));
            return redirect()->route('kendaraan.index')->with('success', 'Data berhasil diimpor.');
            } catch (\Exception $e) {
            Log::error('Kesalahan saat mengimpor data Kendaraan:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal mengimpor data. Silakan periksa format file.');
            }
        } else {
            return redirect()->back()->with('error', 'Gagal mengimpor data melalui API. Silakan coba lagi.');
        }
    }


    public function showImportForm()
    {
        return view('admin.kendaraan.import');
    }

    public function downloadSample()
    {
        $filePath = public_path('samples/sample_kendaraan.xlsx');

        // Pastikan file benar-benar ada sebelum mencoba mengunduh
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath, 'Contoh_Data_Kendaraan.xlsx');
    }

    public function downloadQRCodePDF()
    {
        $kendaraans = Kendaraan::all();
        $qrCodes = [];

        foreach ($kendaraans as $kendaraan) {
            $qrCodes[$kendaraan->id] = QrCode::format('svg')
                ->size(150)
                ->generate("https://iae-tc.app/kendaraan/{$kendaraan->id}");
        }

        // Load view with data
        $pdf = Pdf::loadView('admin.kendaraan.qrcode-pdf', compact('kendaraans', 'qrCodes'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('daftar_qrcode_kendaraan.pdf');
    }

    public function downloadSingleQRCode($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        // Generate QR Code in PNG format
        $qrCode = QrCode::format('svg')
            ->size(150)
            ->generate("https://iae-tc.app/kendaraan/{$kendaraan->id}");

        // Encode the PNG QR Code in base64
        $qrCodeBase64 = base64_encode($qrCode);

        // Load view with data
        $pdf = Pdf::loadView('admin.kendaraan.single-qrcode-pdf', compact('kendaraan', 'qrCodeBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("qrcode_kendaraan_{$kendaraan->plat}.pdf");
    }
}