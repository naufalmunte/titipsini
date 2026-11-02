<?php
namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Services\VerifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- TAMBAHKAN INI, JA!

class VerifyController extends Controller
{
    protected $verifyService;

    public function __construct(VerifyService $verifyService)
    {
        $this->verifyService = $verifyService;
    }

    public function index()
    {
        // Cek dulu, kalau sudah ada datanya, gak usah verifikasi lagi
        if (Auth::user()->verify) {
            return redirect()->route('pelanggan.dashboard');
        }

        return view('pelanggan.verify');
    }

    public function store(Request $request)
    {
        $verifyId = auth()->user()->verify->id ?? null;

        $validated = $request->validate([
            // NIK harus unik, KECUALI untuk data milik kita sendiri
            'nik'            => 'required|digits:16|unique:verifies,nik,' . $verifyId,
            'nama_lengkap'   => 'required|string|max:255',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'tgl_lahir'      => 'required|date',
            'link_identitas' => 'required|url',
        ]);

        $this->verifyService->handleVerification($validated);

        return redirect()->route('pelanggan.dashboard')->with('success', 'Data berhasil diperbarui!');
    }
}