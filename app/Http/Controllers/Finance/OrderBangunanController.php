<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderBangunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderBangunanController extends Controller
{
    /**
     * Menampilkan daftar antrean pembayaran bangunan (List Page)
     */
    public function index()
    {
        // Ambil order dengan tipe bangunan yang statusnya menunggu verifikasi pembayaran
        $orders = Order::where('layanan_type', OrderBangunan::class)
                       ->where('status', 'menunggu_konfirmasi_pembayaran')
                       ->latest()
                       ->get();

        // Mengarah ke resources/views/finance/confirm-bangunan.blade.php
        return view('finance.confirm-bangunan', compact('orders'));
    }

    /**
     * Menampilkan detail pembayaran dan form plotting mitra (Detail Page)
     */
public function show($id)
{
    // Eager load layanan, alamatPelanggan, dan user
    $order = Order::with(['user', 'layanan.alamatPelanggan'])->findOrFail($id);
    
    // Ambil data lokasi bangunan dari tabel alamat_pelanggans (lewat relasi)
    $lokasiAset = $order->layanan->alamatPelanggan;
    $kecamatanOrder = strtolower($lokasiAset->kecamatan ?? '');
    $kotaOrder = strtolower($lokasiAset->kab_kota ?? '');

    // Ambil semua mitra beserta profil_mitra-nya
    $mitras = User::where('role', 'mitra')->with('profilMitra')->get()->map(function($mitra) use ($kecamatanOrder, $kotaOrder) {
        
        // Ambil data lokasi dari tabel profil_mitra
        $profil = $mitra->profilMitra;
        $kecMitra = strtolower($profil->kecamatan ?? '');
        $kotaMitra = strtolower($profil->kab_kota ?? '');

        $mitra->is_match_kecamatan = ($kecMitra != '' && $kecMitra == $kecamatanOrder);
        $mitra->is_match_kota = ($kotaMitra != '' && $kotaMitra == $kotaOrder);
        
        // Tampilkan di objek mitra agar mudah dipanggil di blade
        $mitra->display_kecamatan = $profil->kecamatan ?? 'N/A';
        $mitra->display_kota = $profil->kab_kota ?? 'N/A';

        $mitra->priority = 0;
        if ($mitra->is_match_kota) $mitra->priority = 1;
        if ($mitra->is_match_kecamatan) $mitra->priority = 2;

        return $mitra;
    })->sortByDesc('priority');

    return view('finance.bangunan-detail', compact('order', 'mitras', 'lokasiAset'));
}

    /**
     * Proses Sahkan Pembayaran & Penunjukan Mitra
     */
public function approve(Request $request, $id)
{
    \DB::beginTransaction();
    try {
        $order = Order::findOrFail($id);

        // 1. Update Tabel Orders (Tabel Utama)
        // Status 'diproses' menandakan pembayaran sudah sah di sisi Finance
        $order->update([
            'status'   => 'diproses', 
            'mitra_id' => $request->mitra_id,
        ]);

        // 2. Update Tabel Layanan (OrderBangunan)
        // Di sini kita set status layanan dan mengosongkan penjaga_id
        if ($order->layanan) {
            $order->layanan->update([
                'status'     => 'menunggu_penjaga', // JEDER! Status khusus layanan bangunan
                'penjaga_id' => null 
            ]);
        } else {
            // Pengaman jika relasi putus
            throw new \Exception("Data detail layanan bangunan tidak ditemukan.");
        }

        \DB::commit();
        
        return redirect()->route('finance.order-bangunan.index')
                         ->with('success', 'Jeder! Pembayaran disahkan. Status: Diproses & Menunggu Penjaga.');

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error("Error Approve Finance: " . $e->getMessage());
        
        return back()->with('error', 'Gagal memproses verifikasi: ' . $e->getMessage());
    }
}
}