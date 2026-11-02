<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Pendapatan;
use App\Models\Withdrawal;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function store(Request $request)
{
    $userId = Auth::id();

    // 1. Hitung Saldo Tersedia secara akurat
    $totalPendapatan = Pendapatan::where('mitra_id', $userId)->sum('nominal_mitra');
    $totalDitarik = Withdrawal::where('mitra_id', $userId)
                    ->whereIn('status', ['success', 'pending'])
                    ->sum('nominal');
    $saldoTersedia = $totalPendapatan - $totalDitarik;

    // 2. Validasi dengan Rule 'max' dinamis
    $request->validate([
        'rekening_id' => 'required|exists:rekenings,id',
        'nominal' => [
            'required', 
            'numeric', 
            'min:100000', 
            'max:' . $saldoTersedia // JEDER! Validasi Laravel ikut saldo asli
        ],
    ], [
        'nominal.max' => 'Saldo kamu tidak cukup untuk melakukan penarikan sebesar ini!',
        'nominal.min' => 'Minimal penarikan adalah Rp 100.000!',
    ]);

    // 3. Buat Data Withdrawal
    Withdrawal::create([
        'mitra_id' => $userId,
        'rekening_id' => $request->rekening_id,
        'nominal' => $request->nominal,
        'status' => 'pending',
    ]);

    return redirect()->back()->with('success', 'Pengajuan WD Berhasil! Saldo kamu sekarang diproses oleh Finance.');
}

public function konfirmasiSelesai($id)
{
    // Cari data WD milik mitra yang sedang login
    $wd = \App\Models\Withdrawal::where('id', $id)
            ->where('mitra_id', \Illuminate\Support\Facades\Auth::id())
            ->firstOrFail();

    // Pastikan statusnya saat ini adalah 'disetujui' (sudah di-transfer finance)
    if ($wd->status !== 'disetujui') {
        return redirect()->back()->with('error', 'Dana belum dikonfirmasi oleh Finance!');
    }

    $wd->update(['status' => 'selesai']);

    return redirect()->back()->with('success', 'Konfirmasi Berhasil! Status pencairan telah SELESAI.');
}
}