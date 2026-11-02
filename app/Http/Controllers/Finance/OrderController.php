<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\GudangPenitipan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('status', 'menunggu_konfirmasi_pembayaran') 
            ->where('layanan_type', \App\Models\OrderGudang::class)
            ->with(['user', 'orderGudang'])
            ->latest()
            ->get();

        return view('finance.confirm', compact('orders'));
    }

   public function show($id)
   {
        // 1. Ambil data Order lengkap dengan alamat pelanggan
        $order = Order::with(['user', 'orderGudang.items.kategori', 'orderGudang.alamat'])->findOrFail($id);
        
        // 2. Ambil data wilayah pelanggan (DENGAN NULL CHECK AGAR TIDAK ERROR)
        $alamat = $order->orderGudang->alamat;
        $kecamatanPelanggan = $alamat ? $alamat->kecamatan : '';
        $kotaPelanggan = $alamat ? $alamat->kab_kota : '';

        // 3. Ambil gudang dengan filter bertingkat
        $gudangs = GudangPenitipan::with('jamOperasional')
            ->where('status_verifikasi', 'disetujui')
            ->where('is_aktif', 1)
            ->orderByRaw("
                CASE 
                    WHEN kecamatan = ? THEN 0 
                    WHEN kab_kota = ? THEN 1 
                    ELSE 2 
                END
            ", [$kecamatanPelanggan, $kotaPelanggan]) // Binding data wilayah
            ->get();

        Carbon::setLocale('id');
        $jamSekarang = now()->format('H:i:s');

        return view('finance.confirm-detail', compact('order', 'gudangs', 'jamSekarang'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'gudang_id' => 'required|exists:gudang_penitipans,id',
        ]);

        $order = Order::findOrFail($id);

        return DB::transaction(function () use ($request, $order) {
            $gudang = \App\Models\GudangPenitipan::findOrFail($request->gudang_id);

            // Update status & plotting mitra_id
            $order->update([
                'status' => 'diproses',
                'mitra_id' => $gudang->user_id 
            ]);

            if ($order->orderGudang) {
                $order->orderGudang->update([
                    'gudang_id' => $request->gudang_id,
                    'status'    => 'menunggu_kedatangan'
                ]);
            }

            return redirect()->route('finance.confirm.index')
                ->with('success', 'Pembayaran divalidasi dan Gudang berhasil di-plotting!');
        });
    }
}