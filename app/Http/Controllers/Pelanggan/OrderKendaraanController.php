<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderKendaraan;
use App\Models\AlamatPelanggan;
use App\Models\KategoriKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderKendaraanController extends Controller
{
    /**
     * Menampilkan Form Penitipan Kendaraan
     */
    public function index()
    {
        $alamats = AlamatPelanggan::where('user_id', Auth::id())->get();
        $kategoris = KategoriKendaraan::all();

        return view('pelanggan.titip-kendaraan', compact('alamats', 'kategoris'));
    }

    /**
     * Menyimpan data order baru (Checkout)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'alamat_id'       => 'required|exists:alamat_pelanggans,id',
            'items'           => 'required|array',
            'items.*.kategori_id'    => 'required|exists:kategori_kendaraans,id',
            'items.*.merk_kendaraan' => 'required|string',
            'items.*.nomor_plat'     => 'required|string',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $mulai = Carbon::parse($request->tanggal_mulai);
                $selesai = Carbon::parse($request->tanggal_selesai);
                $total_hari = $mulai->diffInDays($selesai) ?: 1;

                // 1. Simpan ke tabel order_kendaraans
                $layanan = OrderKendaraan::create([
                    'tanggal_mulai'     => $request->tanggal_mulai,
                    'tanggal_selesai'   => $request->tanggal_selesai,
                    'alamat_id'         => $request->alamat_id,
                    'catatan_pelanggan' => $request->catatan_pelanggan,
                ]);

                $grand_total = 0;
                foreach ($request->items as $item) {
                    $kategori = KategoriKendaraan::find($item['kategori_id']);
                    $layanan->items()->create([
                        'kategori_id'    => $kategori->id,
                        'jenis_kendaraan'=> $kategori->nama_kategori,
                        'merk_kendaraan' => $item['merk_kendaraan'],
                        'nomor_plat'     => strtoupper($item['nomor_plat']),
                        'warna'          => $item['warna'] ?? null,
                        'harga_per_hari_saat_ini' => $kategori->harga_per_hari,
                    ]);
                    $grand_total += ($kategori->harga_per_hari * $total_hari);
                }

                // 2. Simpan ke tabel orders (Tabel Pusat)
                Order::create([
                    'invoice_number' => 'INV-KND-' . strtoupper(Str::random(10)),
                    'user_id'        => Auth::id(),
                    'layanan_id'     => $layanan->id,
                    'layanan_type'   => OrderKendaraan::class,
                    'total_bayar'    => $grand_total,
                    'status'         => 'menunggu_konfirmasi_admin',
                ]);

                // JEDER! Ini jembatan otomatis ke halaman Pesanan Saya (Booking Index)
                return redirect()->route('pelanggan.booking.index')
                    ->with('success', 'Order Kendaraan Berhasil, Raja! Silakan tunggu konfirmasi Admin.');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal simpan: ' . $e->getMessage())->withInput();
        }
    }
}