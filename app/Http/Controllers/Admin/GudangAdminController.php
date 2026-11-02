<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GudangService;
use Illuminate\Http\Request;

class GudangAdminController extends Controller
{
    protected $gudangService;

    public function __construct(GudangService $gudangService)
    {
        $this->gudangService = $gudangService;
    }

    // Menampilkan halaman tabel verifikasi
   public function index()
    {
        $gudangs = $this->gudangService->listSemuaGudang();
        // Dari admin.gudang.index menjadi admin.gudang
        return view('admin.gudang', compact('gudangs')); 
    }

    // Memproses persetujuan atau penolakan
    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        $this->gudangService->verifikasiGudang($id, $request->status);

        $pesan = $request->status === 'disetujui' ? 'Gudang berhasil disetujui!' : 'Pengajuan Gudang ditolak.';
        return redirect()->back()->with('success', $pesan);
    }
}