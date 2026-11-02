<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Services\GudangService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GudangController extends Controller
{
    protected $gudangService;

    public function __construct(GudangService $gudangService)
    {
        $this->gudangService = $gudangService;
    }

    public function index()
    {
        $gudangs = $this->gudangService->listGudangMitra(Auth::id());
        return view('mitra.gudang', compact('gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gudang'   => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'provinsi'      => 'required|string',
            'kab_kota'      => 'required|string',
            'kecamatan'     => 'required|string',
            'alamat_lengkap'=> 'required|string',
            'ukuran_gudang' => 'nullable|string',
            'link_foto'     => 'nullable|url',
        ]);

        $this->gudangService->simpanGudang(Auth::id(), $request->all());
        return redirect()->back()->with('success', 'Gudang berhasil ditambahkan dan menunggu verifikasi Admin!');
    }

    // FUNGSI BARU UNTUK EDIT
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_gudang'   => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'provinsi'      => 'required|string',
            'kab_kota'      => 'required|string',
            'kecamatan'     => 'required|string',
            'alamat_lengkap'=> 'required|string',
            'ukuran_gudang' => 'nullable|string',
            'link_foto'     => 'nullable|url',
            'is_aktif'      => 'required|boolean',
        ]);

        $this->gudangService->updateGudang($id, $request->all());
        return redirect()->back()->with('success', 'Data Gudang berhasil diperbarui!');
    }

    // FUNGSI BARU UNTUK HAPUS
    public function destroy($id)
    {
        $this->gudangService->hapusGudang($id);
        return redirect()->back()->with('success', 'Gudang berhasil dihapus!');
    }

    // FUNGSI BARU MENANGKAP KLIK TOGGLE
    public function toggleStatus($id)
    {
        $this->gudangService->toggleStatus($id);
        return redirect()->back()->with('success', 'Status operasional gudang berhasil diubah!');
    }
}