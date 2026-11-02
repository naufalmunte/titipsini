<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\JamOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JamOperasionalController extends Controller
{
    /**
     * Menampilkan halaman pengaturan jam operasional.
     */
    public function index()
    {
        $user = Auth::user();
        $jamOperasional = $user->jamOperasional;
        
        // Generate otomatis jika belum ada di database
        if ($jamOperasional->isEmpty()) {
            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            
            foreach ($hariList as $hari) {
                JamOperasional::create([
                    'user_id'   => $user->id,
                    'hari'      => $hari,
                    'jam_buka'  => '08:00:00',
                    'jam_tutup' => '17:00:00',
                    'is_buka'   => true, // Minggu otomatis tutup
                ]);
            }
            // Ambil ulang data yang baru saja digenerate
            $jamOperasional = $user->jamOperasional; 
        }

        return view('mitra.jam-operasional', compact('jamOperasional'));
    }

    /**
     * Menyimpan pembaruan jam operasional.
     */
    public function update(Request $request)
    {
        $jams = $request->input('jam');

        if (!$jams) {
            return redirect()->back()->with('error', 'Tidak ada data jadwal yang dikirim.');
        }

        foreach ($jams as $id => $data) {
            JamOperasional::where('id', $id)
                ->where('user_id', Auth::id()) // Pastikan Mitra cuma bisa edit jadwalnya sendiri
                ->update([
                    // ⚠️ FIX: Cek apakah nilainya persis '1'
                    'is_buka'   => (isset($data['is_buka']) && $data['is_buka'] == '1') ? 1 : 0,
                    'jam_buka'  => $data['jam_buka'] ?? null,
                    'jam_tutup' => $data['jam_tutup'] ?? null,
                ]);
        }

        return redirect()->back()->with('success', 'Jadwal Operasional berhasil diperbarui!');
    }
}