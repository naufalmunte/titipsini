<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderBangunanController extends Controller
{
public function index()
    {
        $orders = Order::with(['user', 'layanan.items.kategori'])
                       ->where('layanan_type', \App\Models\OrderBangunan::class)
                       ->latest()
                       ->paginate(10);

        // JEDER! Ambil 'alamat_id' dari layanan (OrderBangunan), bukan dari user.id
        $alamatIds = $orders->pluck('layanan.alamat_id')->filter()->unique();

        // Tembak database 1 KALI SAJA untuk mengambil alamat spesifik yang dipilih pelanggan
        $alamatPelanggans = \App\Models\AlamatPelanggan::whereIn('id', $alamatIds)
                                ->get()
                                ->keyBy('id'); // Sekarang kuncinya adalah ID alamat, bukan user_id

        return view('admin.order-bangunan', compact('orders', 'alamatPelanggans'));
    }

    public function konfirmasi($id)
    {
        $order = Order::findOrFail($id);
        
        // Update status pusat agar pelanggan bisa bayar
        $order->update([
            'status' => 'menunggu_pembayaran'
        ]);

        return back()->with('success', 'Jeder! Order Bangunan berhasil dikonfirmasi Admin 🚀');
    }

    public function tolak($id)
    {
         $order = Order::findOrFail($id);
        
         // Update status pusat menjadi ditolak atau dibatalkan (sesuaikan dengan enum databasemu)
         $order->update([
             'status' => 'dibatalkan'
         ]);

         // Saya ganti with('error') menjadi with('success') agar muncul popup hijau notifikasi berhasil ditolak
         return back()->with('success', 'Order Bangunan telah dibatalkan');
    }
}