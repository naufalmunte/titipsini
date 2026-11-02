<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Services\OrderGudangService;
use App\Models\AlamatPelanggan; // Pastikan Model ini diimport
use App\Models\KategoriBarang; // Pastikan Model ini diimport
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderGudangController extends Controller
{
    protected $orderGudangService;

    public function __construct(OrderGudangService $orderGudangService)
    {
        $this->orderGudangService = $orderGudangService;
    }

    /**
     * Tampilkan Form Penitipan Barang
     */
    public function create()
    {
        // Ambil data alamat milik user yang sedang login
        $alamats = AlamatPelanggan::where('user_id', Auth::id())->get();
        
        // Ambil semua kategori barang untuk pilihan di form
        $kategoris = KategoriBarang::all();

        // Pastikan nama view ini sesuai dengan file yang kamu buat tadi
        return view('pelanggan.titip-barang', compact('alamats', 'kategoris'));
    }

    /**
     * Simpan data pesanan (Logika yang sudah kamu buat)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'alamat_id'       => 'required|exists:alamat_pelanggans,id',
            'items'           => 'required|array|min:1',
            'items.*.kategori_barang_id' => 'required|exists:kategori_barangs,id',
            'items.*.jumlah'  => 'required|integer|min:1',
            'items.*.nama_barang_spesifik' => 'required|string|max:255',
            'catatan_pelanggan' => 'nullable|string|max:500',
        ]);

        try {
            $order = $this->orderGudangService->createOrder($request->all(), Auth::id());

            // Ubah route redirect ke dashboard atau history jika route orders.show belum ada
            return redirect()->route('pelanggan.booking.index')
                             ->with('success', 'Pesanan berhasil dibuat dengan nomor invoice: ' . $order->invoice_number);
                             
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }
}