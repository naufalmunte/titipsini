<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Services\ArmadaMitraService;
use App\Models\JenisArmada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArmadaMitraController extends Controller
{
    protected $armadaService;

    public function __construct(ArmadaMitraService $armadaService)
    {
        $this->armadaService = $armadaService;
    }

    public function index()
    {
        // Ambil daftar mobil milik mitra yang sedang login
        $armadas = $this->armadaService->listArmadaMitra(Auth::id());
        
        // Ambil master jenis armada untuk dropdown di form tambah/edit
        $jenisArmadas = JenisArmada::all(); 
        
        return view('mitra.armada', compact('armadas', 'jenisArmadas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_armada_id' => 'required|exists:jenis_armadas,id',
            'plat_nomor'      => 'required|string|unique:armada_mitras,plat_nomor',
            'merek'           => 'required|string|max:255',
            'link_foto'       => 'nullable|url',
        ]);

        // Lempar ke service, status otomatis 'pending' dan 'is_aktif' = false
        $this->armadaService->simpanArmada(Auth::id(), $request->all(), $request->file('link_foto'));
        
        return redirect()->back()->with('success', 'Mobil baru berhasil didaftarkan! Menunggu verifikasi Admin Titipsini.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_armada_id' => 'required|exists:jenis_armadas,id',
            'plat_nomor'      => 'required|string|unique:armada_mitras,plat_nomor,'.$id,
            'merek'           => 'required|string|max:255',
            'link_foto'       => 'nullable|url',
            'status_ketersediaan' => 'required|in:tersedia,sedang_jalan,maintenance',
        ]);

        $this->armadaService->updateArmada($id, $request->all(), $request->file('link_foto'));
        
        return redirect()->back()->with('success', 'Data armada berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->armadaService->hapusArmada($id);
        
        return redirect()->back()->with('success', 'Armada berhasil dihapus dari garasi.');
    }
}