<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\LahanParkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderKendaraanController extends Controller
{
    // Method untuk menampilkan daftar antrean
    public function index()
    {
        $orders = Order::where('status', 'menunggu_konfirmasi_pembayaran')
            ->where('layanan_type', \App\Models\OrderKendaraan::class)
            ->whereNotNull('bukti_pembayaran')
            ->get();

        return view('finance.confirm-kendaraan', compact('orders'));
    }

    public function show($id) 
    {
        // Gunakan eager loading 'layanan.items' agar tidak berat saat looping di Blade
        $order = Order::with('layanan.items.kategori')->findOrFail($id);
        $lahans = LahanParkir::all();
        
        return view('finance.kendaraan-detail', compact('order', 'lahans'));
    }

    public function approve(Request $request, $id)
{
    // 1. Validasi input dasar
    $request->validate([
        'lahan_id' => 'required_if:action,approve|exists:lahan_parkirs,id',
        'action'   => 'required|in:approve,reject'
    ]);

    try {
        return DB::transaction(function () use ($request, $id) {
            $order = Order::with('layanan.items')->findOrFail($id);

            if ($request->action == 'approve') {
                $lahan = LahanParkir::findOrFail($request->lahan_id);
                
                // --- VALIDASI BACKEND: CEK KAPASITAS SEKALI LAGI ---
                $kategoriId = $order->layanan->items->first()->kategori_id;
                $jumlahUnit = $order->layanan->items->count();
                $sisaSlot = $lahan->hitungSisaKapasitas($kategoriId);

                if ($sisaSlot < $jumlahUnit) {
                    throw new \Exception("Maaf Raja, lahan ini baru saja penuh oleh transaksi lain!");
                }
                // ---------------------------------------------------

                // 2. UPDATE TABEL PUSAT (orders)
                // Raja, di sini kita tambahkan mitra_id agar tersambung ke Dashboard Mitra!
                $order->update([
                    'status' => 'diproses',
                    'mitra_id' => $lahan->user_id // Ambil ID pemilik lahan dari tabel lahan_parkirs
                ]);

                // 3. Update Tabel Layanan (order_kendaraans)
                $order->layanan->update([
                    'status'   => 'menunggu_kedatangan',
                    'lahan_id' => $request->lahan_id
                ]);

                $message = 'Transaksi Berhasil Diverifikasi & Plotting Lahan Selesai!';
            } else {
                // Jika Reject (Tolak bukti bayar)
                $order->update([
                    'status' => 'waiting_payment', 
                    'bukti_pembayaran' => null
                ]);
                
                $message = 'Bukti Pembayaran Berhasil Ditolak';
            }

            return redirect()->route('finance.confirm-kendaraan.index')->with('success', $message);
        });

    } catch (\Exception $e) {
        return back()->with('error', 'Aduh! Ada yang salah: ' . $e->getMessage());
    }
}
}