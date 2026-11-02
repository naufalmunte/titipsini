<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now()->toDateString();

        // 1. Logic Auto-Expired: Ambil ID Order yang layanannya OrderGudang dan sudah lewat tanggal selesai
        $expiredOrderIds = Order::where('user_id', $userId)
            ->where('status', 'dititip')
            ->where('layanan_type', 'App\Models\OrderGudang')
            ->whereHas('orderGudang', function($query) use ($now) {
                $query->where('tanggal_selesai', '<', $now);
            })
            ->pluck('id');

        // Update statusnya secara massal ke 'perlu_diambil'
        if($expiredOrderIds->isNotEmpty()){
            Order::whereIn('id', $expiredOrderIds)->update(['status' => 'perlu_diambil']);
        }

        // 2. AMBIL DATA UNTUK VIEW (SINKRON DENGAN BLADE)
        $verify = Auth::user()->verify;
        
        // Tagihan Menunggu Pembayaran
        $needPaymentOrders = Order::where('user_id', $userId)
            ->where('status', 'menunggu_pembayaran')
            ->latest()
            ->get();
            
        // Masa Titip Habis (Perlu Diambil)
        $needActionOrders = Order::where('user_id', $userId)
            ->where('status', 'perlu_diambil')
            ->latest()
            ->get();

        // JEDER! Hitung Jumlah Titipan Aktif Real (Status masih 'dititip')
        $activeOrdersCount = Order::where('user_id', $userId)
            ->where('status', 'dititip')
            ->count();

        // 3. LEMPAR SEMUA VARIABEL KE VIEW
        return view('pelanggan.dashboard', compact(
            'needPaymentOrders', 
            'needActionOrders', 
            'activeOrdersCount', 
            'verify'
        ));
    }
}