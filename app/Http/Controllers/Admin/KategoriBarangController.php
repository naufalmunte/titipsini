<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use Illuminate\Support\Str; 

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategoris = KategoriBarang::orderBy('created_at', 'desc')->get();
        return view('admin.kategori-barang', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori'    => 'required|string|max:255',
            'tarif_per_barang' => 'required|numeric|min:0',
            'biaya_jemput'     => 'required|numeric|min:0',
            'deskripsi'        => 'nullable|string',
            'icon'             => 'nullable|string|max:50',
        ]);

        KategoriBarang::create([
            'nama_kategori'    => $request->nama_kategori,
            'slug'             => Str::slug($request->nama_kategori),
            'tarif_per_barang' => $request->tarif_per_barang,
            'biaya_jemput'     => $request->biaya_jemput,
            'deskripsi'        => $request->deskripsi,
            'icon'             => $request->icon ?? 'box',
        ]);

        return back()->with('success', 'Kategori Barang berhasil ditambahkan! 🚀');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori'    => 'required|string|max:255',
            'tarif_per_barang' => 'required|numeric|min:0',
            'biaya_jemput'     => 'required|numeric|min:0',
            'deskripsi'        => 'nullable|string',
            'icon'             => 'nullable|string|max:50',
        ]);

        $kategori = KategoriBarang::findOrFail($id);
        $kategori->update([
            'nama_kategori'    => $request->nama_kategori,
            'slug'             => Str::slug($request->nama_kategori),
            'tarif_per_barang' => $request->tarif_per_barang,
            'biaya_jemput'     => $request->biaya_jemput,
            'deskripsi'        => $request->deskripsi,
            'icon'             => $request->icon ?? $kategori->icon,
        ]);

        return back()->with('success', 'Kategori Barang berhasil diperbarui! 🚀');
    }

    public function destroy($id)
    {
        KategoriBarang::findOrFail($id)->delete();
        return back()->with('success', 'Kategori Barang berhasil dihapus! 🗑️');
    }
}