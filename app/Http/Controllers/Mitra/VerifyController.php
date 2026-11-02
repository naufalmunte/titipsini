<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Services\VerifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyController extends Controller
{
    protected $verifyService;

    public function __construct(VerifyService $verifyService)
    {
        $this->verifyService = $verifyService;
    }

    public function index()
    {
        // Jika sudah ada data verify, biarkan dashboard yang menangani tampilannya
        if (Auth::user()->verify && in_array(Auth::user()->verify->status, ['pending', 'approved'])) {
            return redirect()->route('mitra.dashboard');
        }

        return view('mitra.verify');
    }

public function store(Request $request)
{
    // 1. Ambil ID verifikasi (jika sudah ada data lama yang rejected)
    $verifyId = auth()->user()->verify->id ?? null;

    // 2. Validasi (Sama ketatnya dengan Pelanggan)
    $validated = $request->validate([
        'nik'            => 'required|digits:16|unique:verifies,nik,' . $verifyId,
        'nama_lengkap'   => 'required|string|max:255',
        'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
        'tgl_lahir'      => 'required|date',
        'link_identitas' => 'required|url',
        'pin'            => 'required|digits:6',
    ]);

    // 3. Tambahkan status 'pending' secara manual agar Service tidak bingung
    $validated['status'] = 'pending';
    $validated['catatan'] = null;

    // 4. Panggil Service Sakti kamu
    $this->verifyService->handleVerification($validated);

    // 5. Lempar kembali ke Dashboard Mitra dengan pesan sukses
    return redirect()->route('mitra.dashboard')->with('success', 'Data verifikasi berhasil dikirim! Mohon tunggu review admin.');
}
}