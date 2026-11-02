<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderBangunan;
use App\Models\OrderBangunanItem;
use App\Models\KategoriBangunan;
use App\Models\AlamatPelanggan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderBangunanService
{
    public function createOrder(array $data, $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            // 1. Hitung Selisih Hari untuk Total Bayar
            $start = Carbon::parse($data['tanggal_mulai']);
            $end = Carbon::parse($data['tanggal_selesai']);
            $totalHari = $start->diffInDays($end);
            if ($totalHari <= 0) $totalHari = 1; // Minimal 1 hari

            // 2. Ambil snapshot Alamat dari tabel alamat_pelanggans
            $alamat = AlamatPelanggan::findOrFail($data['alamat_id']);

            // 3. Simpan Detail Utama Bangunan
           // Di OrderBangunanService.php
            $orderBangunan = OrderBangunan::create([
                'tanggal_mulai'     => $data['tanggal_mulai'],
                'tanggal_selesai'   => $data['tanggal_selesai'],
                'alamat_id'         => $data['alamat_id'], // <--- Gunakan ID langsung dari form
                'catatan_pelanggan' => $data['catatan_pelanggan'] ?? null,
            ]);

            // 4. Ambil Harga Kategori & Simpan Item
            $kategori = KategoriBangunan::findOrFail($data['kategori_id']);
            OrderBangunanItem::create([
                'order_bangunan_id'       => $orderBangunan->id,
                'kategori_id'             => $data['kategori_id'],
                'nama_aset'               => $data['nama_aset'],
                'harga_per_hari_saat_ini' => $kategori->harga_per_hari,
            ]);

            // 5. Hitung Total Biaya
            $totalBayar = $kategori->harga_per_hari * $totalHari;

            // 6. Hubungkan ke Order Pusat (Polymorphic)
            return Order::create([
                'invoice_number' => 'BNG-' . strtoupper(str()->random(8)),
                'user_id'        => $userId,
                'layanan_type'   => OrderBangunan::class,
                'layanan_id'     => $orderBangunan->id,
                'status'         => 'menunggu_konfirmasi_admin', // <--- Sesuai alur kamu
                'total_bayar'    => $totalBayar, // <--- Sekarang tidak 0 lagi
                // Jika di tabel orders ada kolom alamat_lengkap juga, isi di sini:
                // 'alamat_lengkap' => $alamat->alamat_lengkap, 
            ]);
        });
    }
}