<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\QrisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(QrisService $qrisService)
    {
        $userId = Auth::id();
        $now = Carbon::now()->toDateString();

        // 1. --- SATPAM AUTO-CANCEL ---
        // Gunakan whereIn agar mengecek status 'menunggu_pembayaran' ATAU 'menunggu_konfirmasi_admin'
        $expiredOrders = Order::where('user_id', $userId)
            ->whereIn('status', ['menunggu_pembayaran', 'menunggu_konfirmasi_admin']) 
            ->where(function($query) use ($now) {
                $query->orWhereHasMorph('layanan', ['App\Models\OrderGudang', 'App\Models\OrderKendaraan', 'App\Models\OrderBangunan'], function($q) use ($now) {
                    $q->where('tanggal_mulai', '<', $now);
                })
                ->orWhereHasMorph('layanan', ['App\Models\OrderAngkutan'], function($q) use ($now) {
                    $q->where('tanggal_angkut', '<', $now);
                });
            })->get();

        // Update massal jadi dibatalkan
        if($expiredOrders->isNotEmpty()){
            Order::whereIn('id', $expiredOrders->pluck('id'))->update(['status' => 'dibatalkan']);
        }

       // 2. --- AMBIL DATA DENGAN EAGER LOADING ---
        // PERBAIKAN: Tambahkan 'mitra' ke dalam with() karena mitra_id ada di tabel orders!
        $bookings = Order::with(['layanan', 'mitra'])->where('user_id', $userId)->latest()->get();

        // 3. --- GENERATE QRIS ---
        $qrisStatis = "00020101021126690021ID.CO.BANKMANDIRI.WWW01189360000801686368760211716863687620303UMI51440014ID.CO.QRIS.WWW0215ID10243576368430303UMI5204274153033605802ID5909SEVEN INC6012Bantul (Kab)61055519862070703A0163049029";

        // 4. --- LOAD NESTED RELATIONS ---
        foreach ($bookings as $b) {
            if ($b->layanan) {
                // Jangan lupa tambahkan 'gudang' di sini untuk jaga-jaga
                $possibleRelations = [
                    'items', 'kendaraans', 'kendaraan', 'detailKendaraan', 
                    'alamat', 'alamatPelanggan', 'alamatJemput', 'alamatTujuan', 
                    'jenisArmada', 'gudang', 'lahan', 'penjaga',
                ];

                foreach ($possibleRelations as $relation) {
                    if (method_exists($b->layanan, $relation)) {
                        $b->layanan->loadMissing($relation);
                    }
                }
            }

            if ($b->status == 'menunggu_pembayaran') {
                $b->qris_payload = $qrisService->generate($qrisStatis, (int)$b->total_bayar);
            }
        }
        
        return view('pelanggan.booking', compact('bookings'));
    }

    public function konfirmasiPembayaran(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::where('id', $request->order_id)
                      ->where('user_id', auth()->id())
                      ->firstOrFail();

        if ($order->status !== 'menunggu_pembayaran') {
            return redirect()->back()->with('error', 'Pesanan ini sudah tidak dapat menerima pembayaran.');
        }

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $fileName = 'PAY_' . $order->invoice_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('bukti_bayar', $fileName, 'public');

            $order->update([
                'status' => 'menunggu_konfirmasi_pembayaran',
                'bukti_pembayaran' => $filePath
            ]);

            return redirect()->back()->with('success', 'Bukti pembayaran berhasil dikirim, Raja! Tunggu verifikasi Finance ya.');
        }

        return redirect()->back()->with('error', 'Gagal mengupload file bukti pembayaran.');
    }
}