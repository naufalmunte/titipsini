<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use App\Models\KategoriKendaraan;
use App\Models\KategoriBangunan;
use App\Models\JenisArmada; // JEDER! Import model armada
use App\Models\User;        // JEDER! Import model User buat hitung statistik
use App\Models\Order;       // JEDER! Import model Order buat hitung barang aman (Sesuaikan nama modelnya ya kalau beda)
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        // 1. HITUNG DATA ASLI DARI DATABASE UNTUK SECTION 2
        $stats = [
            'users' => User::count(), 
            
            // Misal mitra itu user yang role-nya 'mitra' (Sesuaikan kalau lu pisah tabel mitras)
            'mitra' => User::where('role', 'mitra')->count(), 
            
            // Total barang dari transaksi yang sukses/selesai
            'barang' => Order::where('status', 'selesai')->count(), 
            
            // Rating statis dulu, atau kalau ada tabel review bisa pakai average()
            'rating' => 4.9 
        ];

        // 2. MENGAMBIL DATA KATEGORI UNTUK SECTION 3
        $kategoriBarang = KategoriBarang::all();
        $kategoriKendaraan = KategoriKendaraan::all();
        $kategoriBangunan = KategoriBangunan::all();
        $jenisArmada = JenisArmada::all();

        // 3. LEMPAR SEMUA DATA KE VIEW 'welcome'
        return view('welcome', compact(
            'stats',
            'kategoriBarang', 
            'kategoriKendaraan', 
            'kategoriBangunan', 
            'jenisArmada'
        ));
    }
}