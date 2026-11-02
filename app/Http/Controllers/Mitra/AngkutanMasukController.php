<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\OrderAngkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AngkutanMasukController extends Controller
{
    /**
     * Menampilkan dashboard logistik mitra.
     */
    public function index()
    {
        // Kita ambil semua tugas yang mitra_id-nya adalah mitra yang sedang login
        // Serta memuat relasi yang dibutuhkan agar tidak terjadi N+1 query
        $tugas = OrderAngkutan::whereHas('order', function($q) {
                $q->where('mitra_id', Auth::id());
            })
            ->with(['order.user', 'alamatJemput', 'jenisArmada'])
            ->latest()
            ->get();

        // Di Blade nanti kita tinggal filter menggunakan collection $tugas->where('status', '...')
        return view('mitra.angkutan-masuk', compact('tugas'));
    }

    /**
     * Update status dari 'menunggu_jemputan' menjadi 'proses_angkut'.
     */
    public function konfirmasiJemput($id)
    {
        $tugas = OrderAngkutan::whereHas('order', function($q) {
                $q->where('mitra_id', Auth::id());
            })->findOrFail($id);
        
        // Update status sesuai ENUM di database (proses_angkut)
        $tugas->update([
            'status' => 'proses_angkut'
        ]);

        return redirect()->back()->with('success', 'Berhasil! Segera meluncur ke lokasi penjemputan!');
    }

    /**
     * Update status dari 'proses_angkut' menjadi 'selesai'.
     */
   public function konfirmasiSelesai($id)
{
    \DB::beginTransaction();
    try {
        // Ambil data tugas angkutan beserta order pusatnya
        $tugas = OrderAngkutan::with('order')->whereHas('order', function($q) {
                $q->where('mitra_id', Auth::id());
            })->findOrFail($id);
        
        // 1. Update status menjadi selesai di tabel OrderAngkutan
        $tugas->update([
            'status' => 'selesai'
        ]);

        // 2. Update status di tabel pusat 'orders'
        $tugas->order->update([
            'status' => 'selesai'
        ]);

        // 3. JEDER! AMBIL SETTING PERSEN DINAMIS DARI ADMIN
        $setting = \App\Models\KomisiSetting::first();
        $persenMitra = $setting ? $setting->persen_mitra : 65; // Ambil 50 dari Admin atau default 65
        $persenTitipsini = 100 - $persenMitra;

        // 4. HITUNG BAGI HASIL BERDASARKAN RATE DINAMIS
        $totalPendapatan = (float)$tugas->order->total_bayar;
        $porsiMitra = ($totalPendapatan * $persenMitra) / 100;
        $porsiTitipsini = ($totalPendapatan * $persenTitipsini) / 100;

        // 5. JEDER! SIMPAN KE TABEL PENDAPATAN DENGAN SNAPSHOT PERSEN
        \App\Models\Pendapatan::updateOrCreate(
            ['order_id' => $tugas->order_id],
            [
                'mitra_id'          => Auth::id(),
                'kategori_layanan'  => 'Angkutan',
                'nominal_mitra'     => $porsiMitra,
                'nominal_titipsini' => $porsiTitipsini,
                'persen_mitra'      => $persenMitra, // KUNCI PERSENNYA DISINI!
                'status_payout'     => 'pending',
            ]
        );

        \DB::commit();
        return redirect()->back()->with('success', 'Tugas selesai, bagi hasil ' . $persenMitra . '% telah dicatat.');

    } catch (\Exception $e) {
        \DB::rollBack();
        return redirect()->back()->with('error', 'Gagal memproses penyelesaian: ' . $e->getMessage());
    }
}
}