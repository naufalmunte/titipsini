<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderKendaraan;
use App\Models\LahanParkir;
use Illuminate\Http\Request;
use App\Services\QrisService;
use Carbon\Carbon;

class KendaraanMasukController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Logic otomatis update status overdue (Masa Titip Habis)
        OrderKendaraan::where('status', 'dititip')
            ->whereDate('tanggal_selesai', '<', now())
            ->update(['status' => 'masa_titip_habis']);

        // 2. Ambil Antrean Masuk (Unit sedang menuju Lahan Mitra ini)
        $incomingOrders = Order::with(['user', 'layanan.items.kategori'])
            ->where('status', 'diproses')
            ->whereHasMorph('layanan', [OrderKendaraan::class], function ($query) use ($user) {
                // Pastikan hanya mengambil order yang di-plot ke lahan milik mitra ini
                $query->where('status', 'menunggu_kedatangan')
                      ->whereHas('lahan', function($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->latest()
            ->get();

        // 3. Ambil Stok Unit di Lahan Mitra (Gunakan relasi ke LahanParkir)
        $myLahans = LahanParkir::where('user_id', $user->id)
            ->with(['orderKendaraans' => function($q) {
                $q->whereIn('status', ['dititip', 'masa_titip_habis'])
                  ->with(['items.kategori', 'order.user']);
            }])
            ->get();

        return view('mitra.kendaraan-masuk', compact('incomingOrders', 'myLahans'));
    }

public function konfirmasi(Request $request, $id)
{
    $order = Order::with('layanan')->findOrFail($id);

    if ($order->layanan) {
        // HAPUS BARIS TANGGAL MASUK REAL DI SINI, RAJA!
        $order->layanan->update([
            'status' => 'dititip'
            // Jangan ada kolom lain di sini selain status
        ]);
        
        return redirect()->back()->with('success', 'Unit Kendaraan Berhasil diterima!');
    }

    return redirect()->back()->with('error', 'Data layanan tidak ditemukan.');
}
public function checkout(Request $request, $id, QrisService $qrisService)
{
    // Ambil data order beserta item dan kategorinya untuk hitung denda
    $order = Order::with('layanan.items.kategori')->findOrFail($id);
    $orderLayanan = $order->layanan;

    // Logika hitung denda
    $tglSelesai = \Carbon\Carbon::parse($orderLayanan->tanggal_selesai)->startOfDay();
    $hariIni = \Carbon\Carbon::now()->startOfDay();
    $totalDenda = 0;

    if ($hariIni->gt($tglSelesai)) {
        $hariTelat = abs($hariIni->diffInDays($tglSelesai));
        $tarifHarian = 0;
        foreach ($orderLayanan->items as $item) {
            $tarifHarian += $item->harga_per_hari_saat_ini;
        }
        $totalDenda = $tarifHarian * $hariTelat;
    }

    // CEGATAN DENDA QRIS
    if ($totalDenda > 0 && $request->pembayaran_denda_lunas != '1') {
        $qrisStatis = "00020101021126690021ID.CO.BANKMANDIRI.WWW01189360000801686368760211716863687620303UMI51440014ID.CO.QRIS.WWW0215ID10243576368430303UMI5204274153033605802ID5909SEVEN INC6012Bantul (Kab)61055519862070703A0163049029";
        $qrisPayload = $qrisService->generate($qrisStatis, (int)$totalDenda);

        return redirect()->back()->with([
            'show_qris' => true,
            'denda_amount' => $totalDenda,
            'qris_payload' => $qrisPayload,
            'order_id' => $id 
        ]);
    }

    \DB::beginTransaction();
    try {
        // 1. Hitung Total Akhir
        $totalUangAkhir = (float)$order->total_bayar + (float)$totalDenda;

        // 2. Update Data Order (Pusat)
        $order->update([
            'total_denda' => $totalDenda,
            'total_bayar' => $totalUangAkhir,
            'status' => 'selesai'
        ]);

        // 3. Update Status Layanan Kendaraan
        $orderLayanan->update([
            'status' => 'selesai'
        ]);

        // 4. JEDER! AMBIL PERSENTASE DINAMIS DARI ADMIN
        $setting = \App\Models\KomisiSetting::first();
        $persenMitra = $setting ? $setting->persen_mitra : 65; // Ambil 50 atau default 65
        $persenTitipsini = 100 - $persenMitra;

        // 5. JEDER! SIMPAN OTOMATIS KE TABEL PENDAPATAN DENGAN SNAPSHOT
        \App\Models\Pendapatan::updateOrCreate(
            ['order_id' => $order->id],
            [
                'mitra_id' => $order->mitra_id,
                'kategori_layanan' => 'Kendaraan',
                'nominal_mitra' => ($totalUangAkhir * $persenMitra) / 100,
                'nominal_titipsini' => ($totalUangAkhir * $persenTitipsini) / 100,
                'persen_mitra' => $persenMitra, // KUNCI RATE SAAT INI
                'status_payout' => 'pending',
            ]
        );

        \DB::commit();
        return redirect()->back()->with('success', 'Berhasil! Unit telah keluar dan bagi hasil ' . $persenMitra . '% telah dicatat.');

    } catch (\Exception $e) {
        \DB::rollBack();
        return redirect()->back()->with('error', 'Gagal memproses unit keluar: ' . $e->getMessage());
    }
}
}