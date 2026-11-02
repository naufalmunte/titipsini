<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderGudang;
use App\Models\OrderGudangItem;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OrderGudangService
{
    public function createOrder(array $data, $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            // 1. Hitung Durasi Hari
            $mulai = Carbon::parse($data['tanggal_mulai']);
            $selesai = Carbon::parse($data['tanggal_selesai']);
            $totalHari = $mulai->diffInDays($selesai) ?: 1;

            // 2. Simpan ke Header Detail (order_gudangs)
            $orderGudang = OrderGudang::create([
                'tanggal_mulai' => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_selesai'],
                'alamat_id' => $data['alamat_id'],
                'opsi_penjemputan' => $data['opsi_penjemputan'], // Tambahkan ini (enum)
'catatan_pelanggan' => $data['catatan_pelanggan'] ?? null,            ]);

            $totalSewaBarang = 0;
            $maxOngkirKategori = 0; // Untuk mencari ongkir tertinggi

            // 3. Simpan Item & Hitung Harga
            foreach ($data['items'] as $item) {
                $kategori = KategoriBarang::findOrFail($item['kategori_barang_id']);
                
                // Cari ongkir tertinggi dari kategori yang dipilih
                if ($kategori->biaya_jemput > $maxOngkirKategori) {
                    $maxOngkirKategori = $kategori->biaya_jemput;
                }

                // Rumus Sewa: (Tarif x Jumlah) x Total Hari
                $subTotalItem = ($kategori->tarif_per_barang * $item['jumlah']) * $totalHari;
                $totalSewaBarang += $subTotalItem;

                OrderGudangItem::create([
                    'order_gudang_id' => $orderGudang->id,
                    'kategori_barang_id' => $kategori->id,
                    'nama_barang_spesifik' => $item['nama_barang_spesifik'],
                    'jumlah' => $item['jumlah'],
                    'harga_per_barang_saat_ini' => $kategori->tarif_per_barang,
                ]);
            }

            // 4. Hitung Ongkir Final
            // Jika user pilih 'jemput', ambil ongkir tertinggi. Jika 'antar_sendiri', ongkir = 0.
            $ongkirFinal = ($data['opsi_penjemputan'] === 'jemput') ? $maxOngkirKategori : 0;
            
            $totalBayarAkhir = $totalSewaBarang + $ongkirFinal;

            // 5. Simpan ke Tabel Pusat (orders) menggunakan Morph
         // 5. Simpan ke Tabel Pusat (orders) menggunakan Morph
            $order = Order::create([
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                'user_id'        => $userId,
                'layanan_type'   => get_class($orderGudang),
                'layanan_id'     => $orderGudang->id,
                'total_bayar'    => $totalBayarAkhir,
                'status'         => 'menunggu_konfirmasi_admin', // GANTI JADI INI, RAJA!
                ]);

                return $order;
            }
            );
        }
}