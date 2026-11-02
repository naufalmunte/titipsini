<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\GudangPenitipan;
use App\Models\OrderGudang;
use Illuminate\Http\Request;
use App\Services\QrisService;

class BarangMasukController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Logic otomatis update status overdue
        OrderGudang::where('status', 'dititip')
            ->whereDate('tanggal_selesai', '<', now())
            ->update(['status' => 'masa_titip_habis']);

        // Ambil Antrean Masuk (Sedang menuju gudang)
        $incomingOrders = Order::with(['user', 'orderGudang.gudang'])
            ->where('mitra_id', $user->id)
            ->where('status', 'diproses')
            ->whereHas('orderGudang', function ($query) {
                $query->where('status', 'menunggu_kedatangan');
            })
            ->latest()
            ->get();

        // Ambil Stok Tersimpan (Per Gudang)
        $myGudangs = GudangPenitipan::where('user_id', $user->id)
            ->with(['orderGudangs' => function($q) {
                $q->whereIn('status', ['dititip', 'masa_titip_habis'])
                  ->with(['order.user', 'items.kategori']);
            }])
            ->get();

        return view('mitra.barang-masuk', compact('incomingOrders', 'myGudangs'));
    }

    public function konfirmasi(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->orderGudang) {
            $order->orderGudang->update([
                'status' => 'dititip',
                'tanggal_masuk_real' => now()
            ]);
            
            return redirect()->back()->with('success', 'Barang Berhasil Diterima! ✅');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

// Di dalam function checkout, tambahkan parameter QrisService
public function checkout(Request $request, $id, QrisService $qrisService)
{
    $order = Order::with('orderGudang.items.kategori')->findOrFail($id);
    $orderGudang = $order->orderGudang;

    $tglSelesai = \Carbon\Carbon::parse($orderGudang->tanggal_selesai)->startOfDay();
    $hariIni = \Carbon\Carbon::now()->startOfDay();
    $totalDenda = 0;

    if ($hariIni->gt($tglSelesai)) {
        $hariTelat = abs($hariIni->diffInDays($tglSelesai));
        $tarifHarian = 0;
        foreach ($orderGudang->items as $item) {
            $tarifHarian += ($item->kategori->tarif_per_barang * $item->jumlah);
        }
        $totalDenda = $tarifHarian * $hariTelat;
    }

    // CEGATAN DENDA DENGAN QRIS
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

    \DB::beginTransaction(); // JEDER! Pakai Transaction agar data konsisten
    try {
        // 1. Hitung total uang akhir (Harga Awal + Denda)
        $totalUangAkhir = (float)$order->total_bayar + (float)$totalDenda;

        // 2. Update status order & gudang
        $order->update([
            'total_denda' => $totalDenda,
            'total_bayar' => $totalUangAkhir,
            'status' => 'selesai'
        ]);

        $orderGudang->update([
            'status' => 'diambil', 
            'tanggal_keluar_real' => now()
        ]);

        // 3. JEDER! AMBIL PERSENTASE DINAMIS DARI ADMIN
        $setting = \App\Models\KomisiSetting::first();
        $persenMitra = $setting ? $setting->persen_mitra : 65; // Default 65 jika belum diset
        $persenTitipsini = 100 - $persenMitra;

        // 4. HITUNG BAGI HASIL BERDASARKAN RATE DINAMIS
        $porsiMitra = ($totalUangAkhir * $persenMitra) / 100;
        $porsiTitipsini = ($totalUangAkhir * $persenTitipsini) / 100;

        // 5. JEDER! SIMPAN KE TABEL PENDAPATAN DENGAN SNAPSHOT
        \App\Models\Pendapatan::updateOrCreate(
            ['order_id' => $order->id],
            [
                'mitra_id' => $order->mitra_id,
                'kategori_layanan' => 'Gudang',
                'nominal_mitra' => $porsiMitra,
                'nominal_titipsini' => $porsiTitipsini,
                'persen_mitra' => $persenMitra, // KUNCI RATE SAAT INI
                'status_payout' => 'pending',
            ]
        );

        \DB::commit();
        return redirect()->back()->with('success', 'Berhasil! Denda lunas, barang diambil, dan bagi hasil ' . $persenMitra . '% telah dicatat.');

    } catch (\Exception $e) {
        \DB::rollBack();
        return redirect()->back()->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
    }
}
}