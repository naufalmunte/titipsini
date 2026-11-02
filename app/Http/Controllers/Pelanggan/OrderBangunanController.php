<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Services\OrderBangunanService; // Pastikan Service ini sudah dibuat
use App\Models\AlamatPelanggan; 
use App\Models\KategoriBangunan; // Pastikan Model ini sesuai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderBangunanController extends Controller
{
    protected $orderBangunanService;

    public function __construct(OrderBangunanService $orderBangunanService)
    {
        $this->orderBangunanService = $orderBangunanService;
    }

    /**
     * Tampilkan Form Penitipan Bangunan
     * Menggunakan fungsi index agar sinkron dengan Sidebar & Route
     */
    public function index()
    {
        // Ambil data alamat milik user yang sedang login
        $alamats = AlamatPelanggan::where('user_id', Auth::id())->get();
        
        // Ambil semua kategori bangunan untuk pilihan di form
        $kategoris = KategoriBangunan::all();

        // Mengarah ke file resources/views/pelanggan/titip-bangunan.blade.php
        return view('pelanggan.titip-bangunan', compact('alamats', 'kategoris'));
    }

    /**
     * Simpan data pesanan
     */
    public function store(Request $request)
    {
        // Validasi sesuai input di form titip-bangunan.blade.php
        $request->validate([
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'kategori_id'     => 'required|exists:kategori_bangunans,id',
            'nama_aset'       => 'required|string|max:255',
            'alamat_id'       => 'required|exists:alamat_pelanggans,id',
            'catatan_pelanggan' => 'nullable|string|max:500',
        ]);

        try {
            // Memanggil service untuk memproses logika penyimpanan (DB Transaction)
            $order = $this->orderBangunanService->createOrder($request->all(), Auth::id());

            return redirect()->route('pelanggan.dashboard')
                             ->with('success', 'Pesanan penitipan bangunan berhasil dibuat! Invoice: ' . ($order->invoice_number ?? $order->kode_order));
                             
        } catch (\Exception $e) {
            // Jika gagal, kembali ke form dengan pesan error
            return back()->withInput()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }
}