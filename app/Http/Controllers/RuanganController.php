<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Tempat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RuanganExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RuanganImport;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RuanganController extends Controller
{
    // Menampilkan daftar semua ruangan
    public function index(Request $request)
    {
        $status = $request->input('status');
        $category = $request->input('category');
        $tempatId = $request->input('tempat_id');

        $ruangans = Ruangan::with('tempat')
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($category, function ($query) use ($category) {
                return $query->where('category', $category);
            })
            ->when($tempatId, function ($query) use ($tempatId) {
                return $query->where('tempat_id', $tempatId);
            })
            ->get();

        $tempats = Tempat::all(); // Ambil semua data tempat untuk dropdown

        return view('admin.ruangan.index', compact('ruangans', 'tempats'));
    }

    // Menampilkan form untuk membuat ruangan baru
    public function create()
    {
        $tempats = Tempat::where('category', 'gedung')->get();
        return view('admin.ruangan.create', compact('tempats'));
    }

    // Menyimpan ruangan baru
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:ruangans',
            'status' => 'required|boolean',
            'capacity' => 'required|integer',
            'category' => 'required|in:kelas,lab,gudang',
            'tempat_id' => 'required|exists:tempats,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos/ruangan', 'public');
        }

        Ruangan::create($data);
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    // Menampilkan detail ruangan
    public function show(Ruangan $ruangan)
    {
        $qrCode = QrCode::size(200)->generate("http://iae-tc.app/ruangan/{$ruangan->id}");
        return view('admin.ruangan.show', compact('ruangan', 'qrCode'));
    }

    // Menampilkan form untuk mengedit ruangan
    public function edit(Ruangan $ruangan)
    {
        $tempats = Tempat::where('category', 'gedung')->get();
        return view('admin.ruangan.edit', compact('ruangan', 'tempats'));
    }

    // Mengupdate ruangan yang ada
    public function update(Request $request, Ruangan $ruangan)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:ruangans,code,' . $ruangan->id,
            'status' => 'required|boolean',
            'capacity' => 'required|integer',
            'category' => 'required|in:kelas,lab,gudang',
            'tempat_id' => 'required|exists:tempats,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'nullable|boolean',
        ]);

        $data = $request->all();

        // Hapus foto lama jika diminta
        if ($request->has('remove_photo') && $ruangan->photo) {
            Storage::disk('public')->delete($ruangan->photo);
            $data['photo'] = null;
        }

        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            if ($ruangan->photo) {
                Storage::disk('public')->delete($ruangan->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos/ruangan', 'public');
        }

        $ruangan->update($data);
        return redirect()->route('ruangan .index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    // Menghapus ruangan
    public function destroy(Ruangan $ruangan)
    {
        if ($ruangan->photo) {
            Storage::disk('public')->delete($ruangan->photo);
        }

        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        Ruangan::whereIn('id', $ids)->delete();
        return response()->json(['success' => true]);
    }

    public function downloadPDF()
    {
        $ruangans = Ruangan::all();
        $pdf = Pdf::loadView('admin.ruangan.pdf', compact('ruangans'))
            ->setPaper('a4', 'potrait');

        return $pdf->download('daftar_ruangan.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new RuanganExport, 'daftar_ruangan.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $file = $request->file('file'); // Ambil file dari request
            Excel::import(new RuanganImport($file), $file); // Berikan file ke konstruktor RuanganImport
            return redirect()->route('ruangan.index')->with('success', 'Data berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat mengimpor data Ruangan:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal mengimpor data. Silakan periksa format file.');
        }
    }


    public function showImportForm()
    {
        return view('admin.ruangan.import');
    }

    public function downloadSample()
    {
        $filePath = public_path('samples/sample_ruangan.xlsx');

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath, 'Contoh_Data_Ruangan.xlsx');
    }

    public function downloadQRCodePDF()
    {
        $ruangans = Ruangan::all();
        $qrCodes = [];

        foreach ($ruangans as $ruangan) {
            $qrCodes[$ruangan->id] = QrCode::format('svg')
                ->size(150)
                ->generate("https://iae-tc.app/ruangan/{$ruangan->id}");
        }

        // Load view with data
        $pdf = Pdf::loadView('admin.ruangan.qrcode-pdf', compact('ruangans', 'qrCodes'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('daftar_qrcode_ruangan.pdf');
    }


    public function downloadSingleQRCode($id)
    {
        $ruangan = Ruangan::findOrFail($id);

        // Generate QR Code in PNG format
        $qrCode = QrCode::format('svg')
            ->size(150)
            ->generate("https://iae-tc.app/ruangan/{$ruangan->id}");

        // Encode the PNG QR Code in base64
        $qrCodeBase64 = base64_encode($qrCode);

        // Load view with data
        $pdf = Pdf::loadView('admin.ruangan.single-qrcode-pdf', compact('ruangan', 'qrCodeBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("qrcode_ruangan_{$ruangan->code}.pdf");
    }


}