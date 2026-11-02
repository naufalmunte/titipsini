<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilMitra; // Pastikan model ProfilMitra sudah ada
use Illuminate\Http\Request;

class MitraController extends Controller
{
    /**
     * Menampilkan halaman daftar mitra aktif dari tabel profil_mitra
     */
    public function index(Request $request)
    {
        // Ambil data profil mitra beserta data usernya
        $query = ProfilMitra::with('user');

        // Fitur Pencarian berdasarkan nama PT atau nama/email user
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_pt', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
        }

        // Tampilkan 10 data per halaman terbaru
        $mitras = $query->latest()->paginate(10);

        // Arahkan ke view
        return view('admin.mitra-aktif', compact('mitras'));
    }
}