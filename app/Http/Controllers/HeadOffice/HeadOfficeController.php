<?php

namespace App\Http\Controllers\HeadOffice;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HeadOfficeController extends Controller
{
    public function index()
    {
        $data = [
            'peminjamans' => Peminjaman::count(),
            'detail_peminjamans' => DetailPeminjaman::count(),
        ];

        return view('headoffice.dashboard', compact('data'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('headoffice.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phoneNumber = $request->phoneNumber;
        $user->unit = $request->unit;
        $user->address = $request->address;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Periksa apakah foto akan dihapus
        if ($request->remove_photo == '1' && $user->photo) {
            // Hapus file dari storage
            Storage::disk('public')->delete($user->photo);
            $user->photo = null; // Set kolom photo ke null
        }

        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru
            $user->photo = $request->file('photo')->store('photos', 'public');
        }

        $user->save();

        return redirect()->route('headoffice.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
