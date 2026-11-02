<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class RoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
{
    $user = Auth::user();
    // 1. Cek Login
    if (!$user) {
        return redirect('/login');
    }
    // 2. Gunakan $request->is() daripada $request->segment() karena lebih akurat
    // Cek apakah URL yang diakses dimulai dengan nama role-nya (misal: mitra/*)
    if ($request->is($user->role . '*') || $request->is('/')) {
        return $next($request);
    }

    // 3. Jika user mencoba akses milik role lain (misal Mitra buka /admin/...)
    // Baru kita tendang ke dashboard aslinya
    return redirect('/' . $user->role . '/dashboard');
}
}