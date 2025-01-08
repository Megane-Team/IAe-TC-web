<?php

namespace App\Http\Controllers;

use App\Models\Barang; // Pastikan Anda memiliki model Barang
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\BarangExport; // Pastikan Anda memiliki kelas BarangExport
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangImport; // Pastikan Anda memiliki kelas BarangImport
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarangController extends Controller
{
    // Menampilkan daftar barang berdasarkan ruangan
    public function index(Request $request)
    {
        $status = $request->input('status');
        $condition = $request->input('condition');
        $ruanganId = $request->input('ruangan_id');

        // Ambil barang berdasarkan filter
        $barangs = Barang::with('ruangan')
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($condition, function ($query) use ($condition) {
                return $query->where('condition', $condition);
            })
            ->when($ruanganId, function ($query) use ($ruanganId) {
                return $query->where('ruangan_id', $ruanganId);
            })
            ->get();

        // Ambil semua ruangan untuk dropdown
        $ruangans = Ruangan::all();

        return view('admin.barang.index', compact('barangs', 'ruangans'));
    }

    // Menampilkan form untuk menambah barang
    public function create(Request $request)
    {
        $ruangans = Ruangan::all(); // Ambil semua ruangan untuk dropdown
        return view('admin.barang.create', compact('ruangans'));
    }

    // Menyimpan barang baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'code' => 'required|string|max:50|unique:barangs,code',
            'status' => 'required|boolean',
            'condition' => 'required|in:bagus,kurang_bagus,rusak',
            'warranty' => 'required|date_format:d-m-Y',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ruangan_id' => 'required|exists:ruangans,id',
        ]);

        $data = $request->all();
        $data['warranty'] = Carbon::createFromFormat('d-m-Y', $request->warranty)->format('Y-m-d');

        // Menangani upload foto
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos/barang', 'public');
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit barang
    public function edit(Barang $barang)
    {
        $ruangans = Ruangan::all(); // Ambil semua ruangan untuk dropdown
        return view('admin.barang.edit', compact('barang', 'ruangans'));
    }

    // Memperbarui barang
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'code' => 'required|string|max:50|unique:barangs,code,' . $barang->id,
            'status' => 'required|boolean',
            'condition' => 'required|in:bagus,kurang_bagus,rusak',
            'warranty' => 'required|date_format:d-m-Y',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'nullable|boolean',
            'ruangan_id' => 'required|exists:ruangans,id',
        ]);

        $data = $request->all();
        $data['warranty'] = Carbon::createFromFormat('d-m-Y', $request->warranty)->format('Y-m-d');

        // Hapus foto lama jika diminta
        if ($request->has('remove_photo') && $barang->photo) {
            Storage::disk('public')->delete($barang->photo);
            $data['photo'] = null;
        }

        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            if ($barang->photo) {
                Storage::disk('public')->delete($barang->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos/barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    // Menghapus barang
    public function destroy(Barang $barang)
    {
        if ($barang->photo) {
            Storage::disk('public')->delete($barang->photo);
        }

        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    // Mengambil detail barang untuk modal
    public function show(Barang $barang)
    {
        $qrCode = QrCode::size(200)->generate("http://iae-tc.app/barang/{$barang->id}");
        return view('admin.barang.show', compact('barang', 'qrCode'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        Barang::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data barang berhasil dihapus.']);
    }

    public function downloadPDF()
    {
        $barangs = Barang::all();

        $pdf = Pdf::loadView('admin.barang.pdf', compact('barangs'))
            ->setPaper('a4', 'potrait');

        return $pdf->download('daftar_barang.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new BarangExport, 'daftar_barang.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $file = $request->file('file'); // Ambil file dari request
            Excel::import(new BarangImport($file), $file); // Berikan file ke konstruktor BarangImport
            return redirect()->route('barang.index')->with('success', 'Data berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat mengimpor data Barang:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal mengimpor data. Silakan periksa format file.');
        }
    }


    public function showImportForm()
    {
        return view('admin.barang.import');
    }

    public function downloadSample()
    {
        $filePath = public_path('samples/sample_barang.xlsx');

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath, 'Contoh_Data_Barang.xlsx');
    }
    public function downloadQRCodePDF()
    {
        $barangs = Barang::all();
        $qrCodes = [];

        foreach ($barangs as $barang) {
            $qrCodes[$barang->id] = QrCode::format('svg')
                ->size(150)
                ->generate("https://iae-tc.app/barang/{$barang->id}");
        }

        // Load view with data
        $pdf = Pdf::loadView('admin.barang.qrcode-pdf', compact('barangs', 'qrCodes'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('daftar_qrcode_barang.pdf');
    }

    public function downloadSingleQRCode($id)
    {
        $barang = Barang::findOrFail($id);

        // Generate QR Code in PNG format
        $qrCode = QrCode::format('svg')
            ->size(150)
            ->generate("https://iae-tc.app/barang/{$barang->id}");

        // Encode the PNG QR Code in base64
        $qrCodeBase64 = base64_encode($qrCode);

        // Load view with data
        $pdf = Pdf::loadView('admin.barang.single-qrcode-pdf', compact('barang', 'qrCodeBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("qrcode_barang_{$barang->code}.pdf");
    }
}