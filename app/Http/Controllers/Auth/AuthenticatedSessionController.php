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
            'fcm_token' => 'required|string', 
        ]);

        // make a log for fcm_token 

        $credentials = $request->only('email', 'password');
        $fcmToken = $request->input('fcm_token');

        if (Auth::attempt($credentials)) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post(env('API_URL') . '/users/login', [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'deviceToken' => $fcmToken,
            ]);

            if ($response->successful()) {
                $user = Auth::user();
                if ($user->role == 'admin') {
                    return redirect(route('admin.dashboard'));
                } elseif ($user->role == 'headOffice') {
                    return redirect(route('headoffice.dashboard'));
                }
            } else {
                return back()->withErrors([
                    'email' => 'Email atau password salah'
                ]);
            }
        }
        return back()->withErrors([
            'email' => 'Anda bukan admin'
        ]);
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
