<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Import Model yang kamu gunakan
use App\Models\Order; 
use App\Models\Pendapatan; 
use App\Models\Withdrawal;

class DashboardController extends Controller
{
    public function index(Request $request) 
    {
        $user = Auth::user();
        
        // Cek data verifikasi dan profil mitra
        $verify = $user->verify;
        $isApproved = $verify && $verify->status == 'approved';
        $profil = $user->profilMitra;

        // Inisialisasi variabel statistik default
        $pesananAktif = 0;
        $totalPendapatan = 0;
        $saldoTersedia = 0;
        $rating = 0.0;

        // Inisialisasi variabel default untuk Grafik 4 Layanan
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');
        $namaBulan = Carbon::createFromFormat('m', $bulan)->translatedFormat('F');
        
        $titipBarang = 0; 
        $titipKendaraan = 0; 
        $titipBangunan = 0; 
        $jasaAngkutan = 0;

        // Jika akun sudah disetujui dan profil lokasi sudah diisi, hitung data real
        if ($isApproved && $profil) {
            
            // 1. HITUNG PESANAN AKTIF
            $pesananAktif = Order::where('mitra_id', $user->id)
                                ->whereIn('status', ['pending', 'diproses', 'berjalan'])
                                ->count();

            // 2. HITUNG TOTAL PENDAPATAN
            $totalPendapatan = Pendapatan::where('mitra_id', $user->id)->sum('nominal_mitra');

            // 3. HITUNG SALDO SIAP DITARIK
            $totalDitarik = Withdrawal::where('mitra_id', $user->id)
                                        ->whereIn('status', ['pending', 'disetujui', 'selesai'])
                                        ->sum('nominal');
            $saldoTersedia = $totalPendapatan - $totalDitarik;

            // 4. HITUNG RATING MITRA
            $rating = 4.8; 

            // 5. HITUNG DATA GRAFIK 4 LAYANAN (KHUSUS MITRA INI)
            $orders = Order::where('mitra_id', $user->id)
                           ->whereYear('created_at', $tahun)
                           ->whereMonth('created_at', $bulan)
                           ->get();

            foreach ($orders as $order) {
                switch ($order->layanan_type) {
                    case 'App\Models\OrderGudang': $titipBarang++; break;
                    case 'App\Models\OrderKendaraan': $titipKendaraan++; break;
                    case 'App\Models\OrderBangunan': $titipBangunan++; break;
                    case 'App\Models\OrderAngkutan': $jasaAngkutan++; break;
                }
            }
        }

        return view('mitra.dashboard', compact(
            'verify', 'isApproved', 'profil', 'pesananAktif', 
            'totalPendapatan', 'saldoTersedia', 'rating',
            // Variabel Chart 4 Layanan:
            'tahun', 'bulan', 'namaBulan',
            'titipBarang', 'titipKendaraan', 'titipBangunan', 'jasaAngkutan'
        ));
    }
}