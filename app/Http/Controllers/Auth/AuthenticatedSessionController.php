<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        // 1. Cek email & password user
        $request->authenticate();
        $request->session()->regenerate();

        // 2. Ambil data user yang sedang login
        $user = $request->user();

        // 3. Redirect Pintar ke 4 Dashboard Berbeda
        return match ($user->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'finance'   => redirect()->route('finance.dashboard'),
            'mitra'     => redirect()->route('mitra.dashboard'),
            'pelanggan' => redirect()->route('pelanggan.dashboard'),
            default     => redirect('/'), 
        };
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
