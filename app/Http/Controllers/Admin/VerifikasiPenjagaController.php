<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenjagaBangunan;
use Illuminate\Http\Request;

class VerifikasiPenjagaController extends Controller
{
    // Tampilkan semua data penjaga dari semua Mitra
    public function index()
    {
        // Eager load relasi 'mitra' biar performa database ngebut
        $penjagas = PenjagaBangunan::with('mitra')->latest()->get();
        return view('admin.verifikasi-penjaga', compact('penjagas'));
    }

    // Fungsi Setujui
    public function approve($id)
    {
        $penjaga = PenjagaBangunan::findOrFail($id);
        $penjaga->update([
            'status_verifikasi' => 'approved',
            'catatan_admin' => null // Hapus catatan lama jika ada
        ]);

        return redirect()->back()->with('success', 'Data Penjaga berhasil disetujui!');
    }

    // Fungsi Tolak (Wajib isi catatan)
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500'
        ]);

        $penjaga = PenjagaBangunan::findOrFail($id);
        $penjaga->update([
            'status_verifikasi' => 'rejected',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->back()->with('success', 'Data Penjaga berhasil ditolak.');
    }
}