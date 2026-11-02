<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBangunan;
use Illuminate\Http\Request;

class KategoriBangunanController extends Controller
{
    public function index()
    {
        // Menggunakan nama variabel $kategoris agar sesuai dengan @forelse($kategoris ...)
        $kategoris = KategoriBangunan::latest()->get();
        return view('admin.kategori-bangunan', compact('kategoris'));
    }

    // Fungsi create tidak perlu lagi karena kamu pakai MODAL di halaman index
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori'  => 'required|string|max:255',
            'harga_per_hari' => 'required|numeric|min:0',
            'deskripsi'      => 'nullable|string',
        ]);

        KategoriBangunan::create($data);

        return redirect()->back()->with('success', 'Kategori Bangunan Berhasil Ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriBangunan::findOrFail($id);
        
        $data = $request->validate([
            'nama_kategori'  => 'required|string|max:255',
            'harga_per_hari' => 'required|numeric|min:0',
            'deskripsi'      => 'nullable|string',
        ]);

        $kategori->update($data);

        return redirect()->back()->with('success', 'Data Kategori Berhasil Diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = KategoriBangunan::findOrFail($id);
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori Berhasil Dihapus dari Sistem!');
    }
}