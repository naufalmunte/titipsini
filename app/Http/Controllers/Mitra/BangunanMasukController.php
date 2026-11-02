<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PenjagaBangunan;

class BangunanMasukController extends Controller
{
    /**
     * Tampilkan daftar pesanan bangunan yang butuh penjaga
     */
public function index()
{
    $orders = Order::with(['layanan.items', 'user', 'layanan.alamat'])
        ->where('mitra_id', \Auth::id())
        ->where('status', 'diproses')
        // JEDER! Filter agar hanya mengambil orderan yang tipe layanannya GUDANG / BANGUNAN
        // Sesuaikan string 'App\Models\OrderGudang' dengan namespace model OrderGudang kamu
        ->where('layanan_type', 'App\Models\OrderBangunan') 
        ->latest()
        ->get();

    $penjagas = PenjagaBangunan::all();
    
    return view('mitra.bangunan-masuk', compact('orders', 'penjagas'));
}

    /**
     * Proses penunjukan penjaga untuk aset tersebut
     */
    public function assign(Request $request, $id)
{
    $request->validate([
        'penjaga_id' => 'required|exists:penjaga_bangunans,id'
    ]);

    \DB::beginTransaction();
    try {
        $order = Order::findOrFail($id);

        if ($order->layanan) {
            $order->layanan->update([
                'penjaga_id' => $request->penjaga_id,
                'status'     => 'dalam_pengawasan' // JEDER! Sesuaikan dengan permintaanmu
            ]);
        }

        \DB::commit();
        return redirect()->route('mitra.bangunan-masuk.index')->with('success', 'Staff Berhasil Diterjunkan!');

    } catch (\Exception $e) {
        \DB::rollBack();
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}

public function selesai($id)
{
    \DB::beginTransaction();
    try {
        $order = Order::findOrFail($id);

        // 1. Update Status di tabel Order & OrderBangunan
        $order->update(['status' => 'selesai']);
        if ($order->layanan) {
            $order->layanan->update(['status' => 'selesai']);
        }

        // 2. JEDER! AMBIL PERSENTASE DINAMIS DARI ADMIN
        $setting = \App\Models\KomisiSetting::first();
        $persenMitra = $setting ? $setting->persen_mitra : 65; // Ambil dari setting atau default 65
        $persenTitipsini = 100 - $persenMitra;

        // 3. HITUNG BAGI HASIL BERDASARKAN RATE DINAMIS
        // Total pendapatan = Harga Order + Denda
        $total_pendapatan = $order->total_bayar + ($order->total_denda ?? 0);
        
        $bagi_mitra = ($total_pendapatan * $persenMitra) / 100;
        $bagi_titipsini = ($total_pendapatan * $persenTitipsini) / 100;

        // 4. JEDER! SIMPAN OTOMATIS DENGAN SNAPSHOT PERSEN
        \App\Models\Pendapatan::updateOrCreate(
            ['order_id' => $order->id], // Cari berdasarkan order_id
            [
                'mitra_id'         => $order->mitra_id,
                'kategori_layanan' => 'Bangunan', // Label untuk filter laporan
                'nominal_mitra'    => $bagi_mitra,
                'nominal_titipsini' => $bagi_titipsini,
                'persen_mitra'     => $persenMitra, // KUNCI PERSEN SAAT INI
                'status_payout'    => 'pending',
            ]
        );

        \DB::commit();
        return redirect()->route('mitra.bangunan-masuk.index')
                         ->with('success', 'Tugas selesai, bagi hasil ' . $persenMitra . '% sudah dicatat.');

    } catch (\Exception $e) {
        \DB::rollBack();
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}
}