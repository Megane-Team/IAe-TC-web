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
        // make a log about the request
        Log::info('Request: ' . $request);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'fcm_token' => 'required|string', 
        ]);

        $credentials = $request->only('email', 'password');
        // make a log for credentials
        Log::info('Credentials: ' . json_encode($credentials));
        
        $fcmToken = $request->input('fcm_token');
        // make a log for fcmToken
        Log::info('FCM Token: ' . $fcmToken);

        if (Auth::attempt($credentials)) {
            Log::info('auth accepted');

            $apiUrl = config('app.api_url');
            // make a log for apiUrl
            Log::info('API URL: ' . $apiUrl . '/users/login');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($apiUrl . '/users/login', [
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
                Log::info('auth not accepted');

                return redirect()->route('login')->withErrors([
                    'email' => 'Email atau password salah'
                ]);
            }
        }

        return redirect()->route('login')->withErrors([
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
