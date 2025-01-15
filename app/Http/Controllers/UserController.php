<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
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
            'nik' => 'nullable|string|max:10|unique:users',
            'role' => 'required|in:admin,user,headOffice',
            'unit' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:150',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phoneNumber' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        // make a post request to the api
        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::asMultipart()->withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->post($apiUrl . '/users', [
            [
            'name'     => 'name',
            'contents' => $request->input('name')
            ],
            [
            'name'     => 'email',
            'contents' => $request->input('email')
            ],
            [
            'name'     => 'nik',
            'contents' => $request->input('nik')
            ],
            [
            'name'     => 'role',
            'contents' => $request->input('role')
            ],
            [
            'name'     => 'unit',
            'contents' => $request->input('unit')
            ],
            [
            'name'     => 'address',
            'contents' => $request->input('address')
            ],
            [
            'name'     => 'phoneNumber',
            'contents' => $request->input('phoneNumber')
            ],
            [
            'name'     => 'password',
            'contents' => $request->input('password')
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
            $data['photo'] = $request->file('photo')->store('photos/users', 'public');
            }

            User::create($data);
            return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan pengguna. Silakan coba lagi.');
        }
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
            'nik' => 'nullable|string|max:10|unique:users,nik,' . $user->id,
            'role' => 'required|in:admin,user,headOffice',
            'unit' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:150',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'nullable|boolean',
            'phoneNumber' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8',
        ]);

        $data = $request->all();

        // make a put request to the api
        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::asMultipart()->withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->put($apiUrl . '/users/' . $user->nik, [
            [
            'name'     => 'name',
            'contents' => $request->input('name')
            ],
            [
            'name'     => 'email',
            'contents' => $request->input('email')
            ],
            [
            'name'     => 'nik',
            'contents' => $request->input('nik')
            ],
            [
            'name'     => 'role',
            'contents' => $request->input('role')
            ],
            [
            'name'     => 'unit',
            'contents' => $request->input('unit')
            ],
            [
            'name'     => 'address',
            'contents' => $request->input('address')
            ],
            [
            'name'     => 'phoneNumber',
            'contents' => $request->input('phoneNumber')
            ],
            [
            'name'     => 'password',
            'contents' => $request->input('password')
            ],
            [
            'name'     => 'photo',
            'contents' => $request->hasFile('photo') ? fopen($request->file('photo')->getPathname(), 'r') : null,
            'filename' => $request->hasFile('photo') ? $request->file('photo')->getClientOriginalName() : null
            ]
        ]);

        if ($response->successful()) {
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

            foreach ($data as $key => $value) {
                if (is_null($value) && isset($user->$key)) {
                    $data[$key] = $user->$key;
                }
            }

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('user.index')->with('success', 'Pengguna berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui pengguna. Silakan coba lagi.');
        }
    }

    // Menghapus pengguna
    public function destroy(User $user)
    {
        // make a delete request to the api
        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->delete($apiUrl . '/users/' . $user->nik);

        if ($response->successful()) {
            if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            }

            $user->delete();
            return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus pengguna. Silakan coba lagi.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        $users = User::whereIn('id', $ids)->get();
        $niks = $users->pluck('nik')->toArray();

        $apiUrl = config('app.api_url');
        $apiToken = session('api_token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->delete($apiUrl . '/users/bulk', [
            'niks' => $niks
        ]);

        if ($response->successful()) {
            foreach ($users as $user) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->delete();
            }
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus pengguna secara bulk.']);
        }
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
