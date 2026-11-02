<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User; // Tambahkan ini
use App\Models\Pendapatan; // Tambahkan ini
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. DATA UNTUK GRAFIK BULANAN ---
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');

        $orders = Order::whereYear('created_at', $tahun)
                       ->whereMonth('created_at', $bulan)
                       ->get();

        $titipBarang = 0; $titipKendaraan = 0; $titipBangunan = 0; $jasaAngkutan = 0;

        foreach ($orders as $order) {
            switch ($order->layanan_type) {
                case 'App\Models\OrderGudang': $titipBarang++; break;
                case 'App\Models\OrderKendaraan': $titipKendaraan++; break;
                case 'App\Models\OrderBangunan': $titipBangunan++; break;
                case 'App\Models\OrderAngkutan': $jasaAngkutan++; break;
            }
        }
        $namaBulan = Carbon::createFromFormat('m', $bulan)->translatedFormat('F');

        // --- 2. DATA UNTUK INFORMASI REALTIME ---
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $mitraAktif = User::where('role', 'mitra')->count();
        $titipanBerjalan = Order::whereIn('status', ['diproses', 'dititip'])->count();

        // Logika Format Profit dipindah ke sini biar Blade makin bersih!
        $profitDb = Pendapatan::sum('nominal_titipsini') ?? 0;
        if ($profitDb >= 1000000000) {
            $profitFormat = round($profitDb / 1000000000, 1) . 'M';
        } elseif ($profitDb >= 1000000) {
            $profitFormat = round($profitDb / 1000000, 1) . 'Jt';
        } else {
            $profitFormat = number_format($profitDb, 0, ',', '.');
        }

        // --- 3. KIRIM SEMUA DATA KE VIEW ---
        return view('admin.dashboard', compact(
            'tahun', 'bulan', 'namaBulan',
            'titipBarang', 'titipKendaraan', 'titipBangunan', 'jasaAngkutan',
            'totalPelanggan', 'mitraAktif', 'titipanBerjalan', 'profitFormat' // <-- Variabel baru ditambahkan
        ));
    }
}