<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;

class UserController extends Controller
{
    // Menampilkan daftar semua pengguna
    public function index(Request $request)
    {
        $role = $request->input('role');

        // Ambil daftar unit yang unik dari kolom 'unit' tabel 'users'
        $units = User::select('unit')->distinct()->get();

        $users = User::when($role, function ($query, $role) {
            return $query->where('role', $role);
        })->get();

        return view('admin.user.index', compact('users', 'units'));
    }


    // Menampilkan form untuk membuat pengguna baru
    public function create()
    {
        return view('admin.user.create');
    }

    // Menyimpan pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'role' => 'required|in:admin,user,headOffice',
            'unit' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:150',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phoneNumber' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos/users', 'public');
        }

        User::create($data);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Menampilkan detail pengguna
    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    // Menampilkan form untuk mengedit pengguna
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    // Mengupdate pengguna yang ada
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user,headOffice',
            'unit' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:150',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->all();

        // Hapus foto lama jika diminta
        if ($request->has('remove_photo') && $user->photo) {
            Storage::disk('public')->delete($user->photo);
            $data['photo'] = null;
        }

        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos/users', 'public');
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    // Menghapus pengguna
    public function destroy(User $user)
    {
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        User::whereIn('id', $ids)->delete();
        return response()->json(['success' => true]);
    }

    public function downloadPDF()
    {
        $users = User::all();

        $pdf = Pdf::loadView('admin.user.pdf', compact('users'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('daftar_pengguna.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new UserExport, 'daftar_pengguna.xlsx');
    }
}
