<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ==============================================
        // 1. DATA STATISTIK REAL-TIME (Pindahan dari Blade)
        // ==============================================
        $pendingConfirmation = Order::where('status', 'menunggu_konfirmasi_pembayaran')->count();
        $totalRevenue = Order::whereIn('status', ['diproses', 'dititip', 'selesai'])->sum('total_bayar');
        $aktifDititip = Order::where('status', 'dititip')->count();

        // ==============================================
        // 2. DATA GRAFIK BULANAN (Sama seperti Admin)
        // ==============================================
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

        // ==============================================
        // 3. KIRIM SEMUA DATA KE VIEW
        // ==============================================
        return view('finance.dashboard', compact(
            'pendingConfirmation', 'totalRevenue', 'aktifDititip',
            'tahun', 'bulan', 'namaBulan',
            'titipBarang', 'titipKendaraan', 'titipBangunan', 'jasaAngkutan'
        ));
    }
}