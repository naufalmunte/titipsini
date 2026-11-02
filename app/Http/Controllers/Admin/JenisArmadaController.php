<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\JenisArmadaService;
use Illuminate\Http\Request;

class JenisArmadaController extends Controller
{
    protected $service;

    public function __construct(JenisArmadaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $jenisArmadas = $this->service->getAllJenis();
        return view('admin.jenis-armada', compact('jenisArmadas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'harga_sewa' => 'required|numeric|min:0',
        ]);

        $this->service->simpanJenis($request->all());
        return redirect()->back()->with('success', 'Master Jenis Armada berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'harga_sewa' => 'required|numeric|min:0',
        ]);

        $this->service->updateJenis($id, $request->all());
        return redirect()->back()->with('success', 'Data Jenis Armada berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Peringatan: Pastikan tidak ada mobil mitra yang sedang menggunakan jenis ini
        // Laravel cascadeOnDelete di migration akan otomatis menghapus mobil mitra terkait jika ini dihapus
        $this->service->hapusJenis($id);
        return redirect()->back()->with('success', 'Jenis Armada berhasil dihapus!');
    }
}