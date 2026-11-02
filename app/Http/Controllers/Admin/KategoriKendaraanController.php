<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriKendaraan; 

class KategoriKendaraanController extends Controller
{
    public function index()
    {
        $kategoris = KategoriKendaraan::orderBy('created_at', 'desc')->get();
        return view('admin.kategori-kendaraan', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori'  => 'required|string|max:255',
            'harga_per_hari' => 'required|numeric|min:0',
            'deskripsi'      => 'nullable|string',
            'icon'           => 'nullable|string|max:50',
        ]);

        KategoriKendaraan::create([
            'nama_kategori'  => $request->nama_kategori,
            'harga_per_hari' => $request->harga_per_hari,
            'deskripsi'      => $request->deskripsi,
            'icon'           => $request->icon ?? 'fa-car',
        ]);

        return back()->with('success', 'Kategori Kendaraan berhasil ditambahkan! 🚀');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori'  => 'required|string|max:255',
            'harga_per_hari' => 'required|numeric|min:0',
            'deskripsi'      => 'nullable|string',
            'icon'           => 'nullable|string|max:50',
        ]);

        $kategori = KategoriKendaraan::findOrFail($id);
        $kategori->update([
            'nama_kategori'  => $request->nama_kategori,
            'harga_per_hari' => $request->harga_per_hari,
            'deskripsi'      => $request->deskripsi,
            'icon'           => $request->icon ?? $kategori->icon,
        ]);

        return back()->with('success', 'Kategori Kendaraan berhasil diperbarui! 🚀');
    }

    public function destroy($id)
    {
        KategoriKendaraan::findOrFail($id)->delete();
        return back()->with('success', 'Kategori Kendaraan berhasil dihapus! 🗑️');
    }
}