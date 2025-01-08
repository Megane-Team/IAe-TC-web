<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://192.168.99.43:3000/users/login', [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'deviceToken' => 'your_device_token'
            ]);

            if ($response->successful()) {
                $user = Auth::user();
                if ($user->role == 'admin') {
                    return redirect(route('admin.dashboard'));
                } elseif ($user->role == 'headOffice') {
                    return redirect(route('headoffice.dashboard'));
                }
            } 
        }

        return back()->withErrors([
            'email' => 'Anda bukan admin'
        ]);


        // $request->authenticate();

        // $request->session()->regenerate();

        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);

        // // Mencoba autentikasi pengguna
        // $credentials = $request->only('email', 'password');
        // if (Auth::attempt($credentials)) {
        //     // Jika autentikasi berhasil, periksa peran pengguna
        //     $user = Auth::user();
        //     if ($user->role == 'admin') {
        //         return redirect(route('admin.dashboard'));
        //     } elseif ($user->role == 'headOffice') {
        //         return redirect(route('headoffice.dashboard')); // Pastikan rute ini sudah ada
        //     } 

        // }

        // return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
