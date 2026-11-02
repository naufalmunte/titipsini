<?php

namespace App\Http\Controllers;

use App\Models\Pendapatan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function indexFinance(Request $request)
    {
        $query = Pendapatan::with(['order.user', 'mitra']);

        // Filter Berdasarkan Kategori Layanan
        if ($request->filled('kategori')) {
            $query->where('kategori_layanan', $request->kategori);
        }

        // Filter Berdasarkan Status Payout (Transfer ke Mitra)
        if ($request->filled('status')) {
            $query->where('status_payout', $request->status);
        }

        $laporans = $query->latest()->get();

        // Hitung Ringkasan Statistik
        $totalHutangMitra = Pendapatan::where('status_payout', 'pending')->sum('nominal_mitra');
        $totalLabaTitipsini = Pendapatan::sum('nominal_titipsini');

        return view('finance.laporan-keuangan', compact('laporans', 'totalHutangMitra', 'totalLabaTitipsini'));
    }

    // Fungsi untuk mengubah status pending menjadi dikirim (setelah ditransfer)
    public function konfirmasiPayout($id)
    {
        $pendapatan = Pendapatan::findOrFail($id);
        $pendapatan->update([
            'status_payout' => 'dikirim',
            'waktu_payout' => now()
        ]);

        return redirect()->back()->with('success', 'Status payout berhasil diperbarui!');
    }

    // Tambahkan ini di LaporanController (untuk Finance)
public function indexWithdrawal()
{
    // Mengambil data withdrawal beserta relasi mitra dan rekening
    $withdrawals = \App\Models\Withdrawal::with(['mitra', 'rekening'])
                    ->orderByRaw("FIELD(status, 'pending', 'success', 'rejected')")
                    ->latest()
                    ->get();

    // JEDER! Arahkan ke file yang baru kamu buat
    return view('finance.withdrawal', compact('withdrawals'));
}

public function updateStatus(Request $request, $id)
{
    $wd = \App\Models\Withdrawal::findOrFail($id);
    
    $request->validate([
        'status' => 'required|in:disetujui,ditolak', // JEDER! Harus sesuai database
        'catatan_admin' => 'nullable|string',
        'bukti_transfer' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $data = ['status' => $request->status, 'catatan_admin' => $request->catatan_admin];

    if ($request->hasFile('bukti_transfer')) {
        $file = $request->file('bukti_transfer');
        $filename = 'WD_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/bukti_transfer'), $filename);
        $data['bukti_transfer'] = $filename;
    }

    $wd->update($data);
    return redirect()->back()->with('success', 'Status Berhasil diperbarui!');
}
}