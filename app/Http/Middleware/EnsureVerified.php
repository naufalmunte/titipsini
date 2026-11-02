<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
public function handle(Request $request, Closure $next)
{
    $user = auth()->user();

    // 1. Ambil status dari relasi tabel 'verifies'
    // Kita cek apakah relasinya ada, lalu ambil kolom 'status'-nya
    $statusVerifikasi = ($user && $user->verify) ? $user->verify->status : 'pending';

    // 2. JEDER! Jika statusnya BUKAN 'approved', maka akses dikunci
    if ($statusVerifikasi !== 'approved') {
        
        // Izinkan lewat JIKA rutenya adalah verifikasi atau logout
        if ($request->is('pelanggan/verify*') || $request->is('mitra/verify*') || $request->is('logout')) {
            return $next($request);
        }

        // Kalau belum approved, tendang ke halaman verifikasi
        $route = ($user->role === 'mitra') ? 'mitra.verify.index' : 'pelanggan.verify.index';
        return redirect()->route($route)->with('warning', 'Sabar ya Raja! Tunggu Admin konfirmasi data dirimu dulu. ⏳');
    }

    // 3. Kalau sudah 'approved', silakan lewat ke menu mana saja!
    return $next($request);
}
}
