<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Services\LahanParkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LahanParkirController extends Controller
{
    protected $parkirService;

    // Inject Service ke dalam Controller
    public function __construct(LahanParkirService $parkirService)
    {
        $this->parkirService = $parkirService;
    }

    public function index()
    {
        $lahan_parkirs = $this->parkirService->listParkirMitra(Auth::id());
        return view('mitra.lahan-parkir', compact('lahan_parkirs'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'nama_lahan'       => 'required|string|max:255',
            'provinsi'         => 'required|string',
            'kab_kota'         => 'required|string',
            'kecamatan'        => 'required|string',
            'alamat_lengkap'   => 'required|string', // 👈 INI YANG SEBELUMNYA KELUPAAN
            'kapasitas_roda_2' => 'required|numeric|min:0',
            'kapasitas_roda_3' => 'required|numeric|min:0',
            'kapasitas_roda_4' => 'required|numeric|min:0',
            'link_foto'        => 'nullable|url',
        ]);

        $this->parkirService->simpanParkir(Auth::id(), $request->all());
        return redirect()->back()->with('success', 'Lahan Parkir berhasil diajukan dan menunggu verifikasi Admin!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lahan'       => 'required|string|max:255',
            'provinsi'         => 'required|string',
            'kab_kota'         => 'required|string',
            'kecamatan'        => 'required|string',
            'alamat_lengkap'   => 'required|string', // 👈 TAMBAHKAN JUGA DI SINI
            'kapasitas_roda_2' => 'required|numeric|min:0',
            'kapasitas_roda_3' => 'required|numeric|min:0',
            'kapasitas_roda_4' => 'required|numeric|min:0',
            'link_foto'        => 'nullable|url',
        ]);

        $this->parkirService->updateParkir($id, $request->except(['_token', '_method']));
        return redirect()->back()->with('success', 'Data Lahan Parkir berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $this->parkirService->toggleStatus($id);
        return redirect()->back()->with('success', 'Status operasional lahan berhasil diubah!');
    }

    public function destroy($id)
    {
        $this->parkirService->hapusParkir($id);
        return redirect()->back()->with('success', 'Lahan Parkir berhasil dihapus!');
    }
}