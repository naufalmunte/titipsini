<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderAngkutan;
use App\Models\AlamatPelanggan;
use App\Models\JenisArmada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderAngkutanController extends Controller
{
    /**
     * Menampilkan halaman form order jasa angkutan
     */
    public function index()
    {
        // Ambil alamat milik user yang sedang login
        $alamats = AlamatPelanggan::where('user_id', Auth::id())->get();
        
        // Ambil semua jenis armada (Pick Up, Truk, dll)
        $jenisArmadas = JenisArmada::all();

        // Mengarah ke file: resources/views/pelanggan/jasa-angkutan.blade.php
        return view('pelanggan.jasa-angkutan', compact('alamats', 'jenisArmadas'));
    }

    /**
     * Memproses penyimpanan order ke 2 tabel sekaligus (orders & order_angkutans)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'jenis_armada_id'  => 'required|exists:jenis_armadas,id',
            'alamat_jemput_id' => 'required|exists:alamat_pelanggans,id',
            'alamat_tujuan_id' => 'required|exists:alamat_pelanggans,id',
            'tanggal_angkut'   => 'required|date|after_or_equal:today',
            'jam_angkut'       => 'required',
            'catatan_muatan'   => 'required|string',
        ]);

        try {
            // Gunakan Transaction agar jika satu gagal, semua dibatalkan
            return DB::transaction(function () use ($request) {
                
                // 2. Ambil data pendukung dari DB
                $jemput = AlamatPelanggan::findOrFail($request->alamat_jemput_id);
                $tujuan = AlamatPelanggan::findOrFail($request->alamat_tujuan_id);
                $armada = JenisArmada::findOrFail($request->jenis_armada_id);

                // 3. Hitung Jarak (KM) dengan rumus Haversine
                $jarak = $this->calculateDistance(
                    $jemput->latitude, $jemput->longitude,
                    $tujuan->latitude, $tujuan->longitude
                );

                // 4. Hitung Total Bayar
                // Rumus: Harga Sewa Dasar + (Jarak * Tarif per KM)
                $tarifPerKm = $armada->tarif_per_km ?? 5000; // Fallback ke 5000 jika kolom belum ada
                $totalBayar = $armada->harga_sewa + ($jarak * $tarifPerKm);

                // 5. SIMPAN KE TABEL DETAIL (order_angkutans)
                $detail = OrderAngkutan::create([
                    'jenis_armada_id'  => $request->jenis_armada_id,
                    'alamat_jemput_id' => $request->alamat_jemput_id,
                    'alamat_tujuan_id' => $request->alamat_tujuan_id,
                    'jarak_km'         => $jarak,
                    'tanggal_angkut'   => $request->tanggal_angkut,
                    'jam_angkut'       => $request->jam_angkut,
                    'catatan_muatan'   => $request->catatan_muatan,
                ]);

                // 6. SIMPAN KE TABEL PUSAT (orders)
                // Menggunakan Polymorphic Relationship
                // 6. SIMPAN KE TABEL PUSAT (orders)
                $order = Order::create([
                    'invoice_number' => 'INV-' . strtoupper(uniqid()),
                    'user_id'        => Auth::id(),
                    'layanan_type'   => OrderAngkutan::class, 
                    'layanan_id'     => $detail->id,
                    'status'         => 'menunggu_konfirmasi_admin', // GANTI DARI pending_assignment KE INI
                    'total_bayar'    => $totalBayar,
                ]);

                // 7. Redirect (Gue arahkan balik ke index jasa angkutan dulu biar aman)
                return redirect()->route('pelanggan.order_angkutan.index')
                                 ->with('success', 'Pesanan berhasil dibuat, Raja! Segera kami proses.');
            });

        } catch (\Exception $e) {
            // Jika ada error (misal: koordinat bermasalah atau db error)
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Waduh Raja, ada masalah teknis: ' . $e->getMessage());
        }
    }

    /**
     * Rumus Haversine untuk menghitung jarak antara dua koordinat (KM)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        $jarakGarisLurus = $earthRadius * $c;
        
        // Tambahkan faktor koreksi rute jalan raya 15%
        $jarakJalanRaya = $jarakGarisLurus * 1.15;

        return round($jarakJalanRaya, 2);
    }
}