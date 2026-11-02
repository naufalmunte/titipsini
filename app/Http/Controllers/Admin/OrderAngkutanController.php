<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderAngkutan;
use App\Models\AlamatPelanggan;
use Illuminate\Http\Request;

class OrderAngkutanController extends Controller
{
    /**
     * Menampilkan daftar pesanan angkutan
     */
    public function index()
    {
        // 1. Ambil order dengan pagination
        $orders = Order::where('layanan_type', OrderAngkutan::class)
                       ->with(['layanan.jenisArmada', 'user'])
                       ->latest()
                       ->paginate(10);

        // 2. Kumpulkan SEMUA ID Alamat (Jemput & Tujuan) dari tabel order_angkutans
        $alamatIds = collect();
        foreach ($orders as $order) {
            if ($order->layanan) {
                $alamatIds->push($order->layanan->alamat_jemput_id);
                $alamatIds->push($order->layanan->alamat_tujuan_id);
            }
        }
        $alamatIds = $alamatIds->filter()->unique();

        // 3. Tarik data alamat 1 KALI SAJA untuk semua titik jemput dan tujuan
        $alamats = AlamatPelanggan::whereIn('id', $alamatIds)->get()->keyBy('id');

        return view('admin.order-angkutan', compact('orders', 'alamats'));
    }

    /**
     * Aksi Konfirmasi (Terima)
     */
    public function konfirmasi($id)
    {
        $order = Order::findOrFail($id);
        
        $order->update([
            'status' => 'menunggu_pembayaran'
        ]);

        return redirect()->back()->with('success', 'Jeder! Pesanan dikonfirmasi! Pelanggan sekarang bisa membayar. 🚀');
    }

    /**
     * Aksi Tolak
     */
    public function tolak($id)
    {
        $order = Order::findOrFail($id);
        
        $order->update([
            'status' => 'dibatalkan'
        ]);

        return redirect()->back()->with('success', 'Order Angkutan telah dibatalkan');
    }
}