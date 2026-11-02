<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Pendapatan; // Pastikan model Pendapatan sudah ada
use App\Models\Withdrawal; // Pastikan model Withdrawal sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function indexLaporanMitra()
{
    $userId = Auth::id();

    // 1. Total Pendapatan Tetap Sama
    $totalPendapatan = Pendapatan::where('mitra_id', $userId)->sum('nominal_mitra');

    // 2. JEDER! Total yang ditarik harus menghitung semua yang "keluar" atau "sedang diproses"
    $totalDitarik = Withdrawal::where('mitra_id', $userId)
                    ->whereIn('status', ['pending', 'disetujui', 'selesai']) // Tambahkan 'disetujui' & 'selesai'
                    ->sum('nominal');

    // 3. Saldo Tersedia otomatis akan berkurang secara real-time
    $saldoTersedia = $totalPendapatan - $totalDitarik;

    // 4. Untuk tampilan "Total Telah Dicairkan" di Card (Hanya yang sudah benar-benar selesai/disetujui)
    $totalSuksesCair = Withdrawal::where('mitra_id', $userId)
                        ->whereIn('status', ['disetujui', 'selesai'])
                        ->sum('nominal');

    $pendapatans = Pendapatan::where('mitra_id', Auth::id())
                ->latest()
                ->paginate(10);

    return view('mitra.laporan', compact(
        'totalPendapatan', 
        'totalDitarik', 
        'saldoTersedia', 
        'pendapatans',
        'totalSuksesCair' // Kirim ini jika ingin nampilin yang benar-benar cair saja di card kanan
    ));
}
}