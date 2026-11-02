<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Rekening; // JEDER! Tambahkan ini agar sistem tahu di mana modelnya
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan

class RekeningController extends Controller
{
    public function index()
    {
        $rekenings = Rekening::where('user_id', Auth::id())
                    ->orderBy('is_utama', 'desc') // Biar yang utama muncul paling depan
                    ->get();

        return view('mitra.rekening', compact('rekenings'));
    }

    // 2. SIMPAN REKENING BARU
    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|numeric',
            'atas_nama' => 'required|string|max:255',
        ]);

        // JEDER! Jika diset jadi utama, matikan status 'is_utama' rekening lain milik user ini
        if ($request->has('is_utama')) {
            Rekening::where('user_id', Auth::id())->update(['is_utama' => false]);
        }

        Rekening::create([
            'user_id' => Auth::id(),
            'nama_bank' => strtoupper($request->nama_bank),
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => strtoupper($request->atas_nama),
            'is_utama' => $request->has('is_utama') ? true : false,
        ]);

        return redirect()->back()->with('success', 'Rekening Berhasil Didaftarkan!');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_bank' => 'required|string',
        'nomor_rekening' => 'required|numeric',
        'atas_nama' => 'required|string',
    ]);

    $rek = Rekening::where('user_id', Auth::id())->findOrFail($id);

    if ($request->has('is_utama')) {
        Rekening::where('user_id', Auth::id())->update(['is_utama' => false]);
    }

    $rek->update([
        'nama_bank' => strtoupper($request->nama_bank),
        'nomor_rekening' => $request->nomor_rekening,
        'atas_nama' => strtoupper($request->atas_nama),
        'is_utama' => $request->has('is_utama'),
    ]);

    return redirect()->back()->with('success', 'Data Rekening Diperbarui! ⚡');
}

    // 3. HAPUS REKENING
    public function destroy($id)
    {
        $rekening = Rekening::where('user_id', Auth::id())->findOrFail($id);
        $rekening->delete();

        return redirect()->back()->with('success', 'Rekening Berhasil Dihapus! ⚡');
    }
}
