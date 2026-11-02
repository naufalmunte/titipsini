<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfilMitraComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // 1. Cek apakah dia Mitra dan sudah Approved verifikasinya
        if ($user && $user->role === 'mitra' && $user->verify?->status === 'approved') {
            
            // 2. Jika belum punya profil mitra dan tidak sedang di halaman isi profil
            if (!$user->profilMitra && !$request->routeIs('mitra.profil.*')) {
                return redirect()->route('mitra.profil.create')
                    ->with('info', 'Selamat! Verifikasi disetujui. Sekarang lengkapi profil PT Anda.');
            }
        }

        return $next($request);
    }
}
