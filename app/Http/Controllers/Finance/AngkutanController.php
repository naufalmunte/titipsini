<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderAngkutan;
use Illuminate\Http\Request;

class AngkutanController extends Controller
{
    /**
     * Menampilkan daftar antrean (confirm-angkutan.blade.php)
     */
    public function index()
    {
        $orders = Order::where('layanan_type', OrderAngkutan::class)
            ->where('status', 'menunggu_konfirmasi_pembayaran') 
            ->whereNotNull('bukti_pembayaran')
            ->with(['user', 'layanan.jenisArmada'])
            ->latest()
            ->get();

        // NAMA VIEW SESUAI FILE KAMU
        return view('finance.confirm-angkutan', compact('orders')); 
    }

    /**
     * Menampilkan detail verifikasi (angkutan-detail.blade.php)
     */
   public function show($id)
   {
        // 1. Ambil data order & layanan (tanpa with alamatJemput karena kita panggil manual)
        $order = Order::with(['user', 'layanan.jenisArmada'])->findOrFail($id);
        
        // 2. JEMPUT DATA ALAMAT MANUAL PAKAI ID
        // (Ganti 'alamat_id' di bawah ini dengan nama kolom aslimu, misal: 'alamat_pelanggan_id')
        $id_alamat_tujuan = $order->layanan->alamat_id ?? null;
        
        // Query ke tabel alamat pelanggans
        $alamatJemput = \App\Models\AlamatPelanggan::find($id_alamat_tujuan);
        
        // Cegah error dengan Null Coalescing
        $kecJemput = $alamatJemput->kecamatan ?? '';
        $kabJemput = $alamatJemput->kab_kota ?? '';
        $provJemput = $alamatJemput->provinsi ?? '';

        // 3. Query Mitra
        $mitras = User::where('role', 'mitra')
            ->whereHas('armada', function($q) use ($order) {
                $jenisArmadaId = $order->layanan->jenis_armada_id ?? 0;
                $q->where('jenis_armada_id', $jenisArmadaId)
                  ->where('status_ketersediaan', 'tersedia')
                  ->where('status_verifikasi', 'disetujui');
            })
            ->join('profil_mitra', 'users.id', '=', 'profil_mitra.user_id')
            ->select('users.*')
            ->orderByRaw("
                CASE 
                    WHEN profil_mitra.kecamatan = ? THEN 0 
                    WHEN profil_mitra.kab_kota = ? THEN 1 
                    WHEN profil_mitra.provinsi = ? THEN 2
                    ELSE 3 
                END ASC
            ", [$kecJemput, $kabJemput, $provJemput])
            ->paginate(9); 

        // 4. Lempar data $alamatJemput ke Blade
        return view('finance.angkutan-detail', compact('order', 'mitras', 'alamatJemput'));
   }
    /**
     * Aksi Approve/Reject
     */
   public function approve(Request $request, $id)
{
    $order = Order::findOrFail($id);

    if ($request->action == 'approve') {
        $request->validate(['mitra_id' => 'required|exists:users,id']);

        // JEDER! Gunakan Transaksi agar data aman
        \DB::transaction(function () use ($request, $order) {
            // 1. Update tabel pusat orders
            $order->update([
                'status' => 'diproses',
                'mitra_id' => $request->mitra_id
            ]);

            // 2. Update tabel detail (order_angkutans)
            // Pastikan relasi 'layanan' sudah terdefinisi di model Order
            $order->layanan->update([
                'mitra_id' => $request->mitra_id,
                'status'   => 'menunggu_jemputan' 
            ]);
        });

        return redirect()->route('finance.confirm-angkutan.index')
                         ->with('success', 'Pembayaran divalidasi dan mitra berhasil ditugaskan!');
    }

    // Jika Reject
    $order->update(['status' => 'menunggu_pembayaran']); // Status balik ke awal agar user bisa upload ulang
    return redirect()->route('finance.confirm-angkutan.index')->with('success', 'Pembayaran ditolak.');
}
}