<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ArmadaMitraService;
use Illuminate\Http\Request;

class ArmadaController extends Controller
{
    protected $armadaService;

    public function __construct(ArmadaMitraService $armadaService)
    {
        $this->armadaService = $armadaService;
    }

    public function index()
    {
        // Ambil SEMUA mobil dari semua mitra untuk di-review Admin
        $armadas = $this->armadaService->listSemuaArmada();
        
        return view('admin.armada', compact('armadas'));
    }

    public function verifikasi(Request $request, $id)
    {
        // Keamanan: Pastikan input dari tombol hanya boleh 'disetujui' atau 'ditolak'
        $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        // Lempar ke service: Kalau disetujui, is_aktif otomatis true. Kalau ditolak, false.
        $this->armadaService->verifikasiArmada($id, $request->status);

        return redirect()->back()->with('success', 'Status verifikasi armada berhasil diperbarui!');
    }
}