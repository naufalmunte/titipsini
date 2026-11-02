<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderGudang;
use App\Models\AlamatPelanggan;
use Illuminate\Http\Request;

class OrderGudangController extends Controller
{
    /**
     * Menampilkan daftar pesanan masuk (Semua Riwayat)
     */
    public function index()
    {
        $orders = Order::where('layanan_type', OrderGudang::class)
                       ->with(['user', 'layanan.items']) 
                       ->latest()
                       ->paginate(10);

        // 1. Tarik Alamat Utama (Sebagai Jaminan Anti-Kosong)
        $userIds = $orders->pluck('user_id')->filter()->unique();
        $alamatPelanggans = AlamatPelanggan::whereIn('user_id', $userIds)->get()->keyBy('user_id');

        // 2. Tarik Alamat Spesifik (Jika pelanggan ganti titik jemput)
        $alamatIds = collect();
        foreach ($orders as $order) {
            $layanan = $order->orderGudang ?? $order->layanan;
            if ($layanan && $layanan->alamat_id) {
                $alamatIds->push($layanan->alamat_id);
            }
        }
        $alamatSpesifik = AlamatPelanggan::whereIn('id', $alamatIds->filter()->unique())->get()->keyBy('id');

        return view('admin.order-gudang', compact('orders', 'alamatPelanggans', 'alamatSpesifik'));
    }

    public function confirm($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'menunggu_konfirmasi_admin') {
            $order->update(['status' => 'menunggu_pembayaran']);
            return back()->with('success', 'Jeder! Pesanan disetujui! Pelanggan sekarang bisa melakukan pembayaran. 🚀');
        }

        return back()->with('error', 'Status tidak valid atau sudah diproses.');
    }

    public function tolak($id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status === 'menunggu_konfirmasi_admin') {
            $order->update(['status' => 'dibatalkan']);
            return back()->with('success', 'Order Gudang telah dibatalkan');
        }

        return back()->with('error', 'Status tidak valid atau sudah diproses.');
    }
}