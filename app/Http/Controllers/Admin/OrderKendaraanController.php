<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\LahanParkir;
use App\Models\OrderKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderKendaraanController extends Controller
{
    /**
     * Menampilkan daftar semua histori order kendaraan
     */
    public function index()
    {
        // 1. Ambil data order beserta relasinya
        $orders = Order::with(['user', 'layanan.items'])
            ->where('layanan_type', OrderKendaraan::class)
            ->latest()
            ->paginate(10);

        // 2. Kumpulkan user_id untuk mencari nomor telepon di tabel alamat_pelanggans
        $userIds = $orders->pluck('user_id')->filter()->unique();

        // 3. Tembak database 1 KALI SAJA untuk mengambil nomor telepon
        $alamatPelanggans = \App\Models\AlamatPelanggan::whereIn('user_id', $userIds)
                                ->get()
                                ->keyBy('user_id');

        return view('admin.order-kendaraan', compact('orders', 'alamatPelanggans'));
    }

    /**
     * Eksekusi Konfirmasi: Update status ke pembayaran
     */
    public function konfirmasi($id)
    {
        DB::transaction(function () use ($id) {
            $order = Order::findOrFail($id);

            // Update Tabel Pusat ke Menunggu Pembayaran
            $order->update(['status' => 'menunggu_pembayaran']);
        });

        return redirect()->back()->with('success', 'Konfirmasi Admin berhasil! Menunggu pembayaran pelanggan, Raja. 🚀');
    }

    /**
     * Eksekusi Tolak: Batalkan pesanan
     */
    public function tolak($id)
    {
         $order = Order::findOrFail($id);
        
         // Update status pusat menjadi ditolak 
         $order->update([
             'status' => 'dibatalkan'
         ]);

         return redirect()->back()->with('success', 'Order Kendaraan telah dibatalkan');
    }
}