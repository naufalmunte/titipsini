<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\ProfilMitra;
use App\Models\JamOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function create()
    {
        $profil = Auth::user()->profilMitra;
        return view('mitra.profil_lengkapi', compact('profil'));
    }

    /**
     * Menyimpan atau memperbarui data profil dengan integrasi Wilayah & Peta.
     */
    public function store(Request $request)
    {
        // 1. VALIDASI DATA (Tambahkan field baru di sini)
        $request->validate([
            'nama_pt'   => 'required|string|max:255',
            'provinsi'  => 'required|string|max:255',
            'kab_kota'  => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'alamat'    => 'required|string|max:500',
            'latitude'  => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        // 2. SIMPAN ATAU UPDATE PROFIL (Gunakan updateOrCreate)
        ProfilMitra::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nama_pt'   => $request->nama_pt,
                'provinsi'  => $request->provinsi,
                'kab_kota'  => $request->kab_kota,
                'kecamatan' => $request->kecamatan,
                'alamat'    => $request->alamat,
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        // 3. GENERATE JAM OPERASIONAL OTOMATIS (Jika belum ada)
        $user = Auth::user();
        if ($user->jamOperasional()->count() == 0) {
            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            
            foreach ($hariList as $hari) {
                JamOperasional::create([
                    'user_id'   => $user->id,
                    'hari'      => $hari,
                    'jam_buka'  => '08:00:00',
                    'jam_tutup' => '17:00:00',
                    'is_buka'   => true,
                ]);
            }
        }

        // 4. ARAHKAN KEMBALI
        return redirect()->route('mitra.profil.edit')->with('success', 'Profil Bisnis dan Lokasi Kantor berhasil diperbarui!');
    }

    public function edit()
    {
        $user = auth()->user();
        $profil = $user->profilMitra;
        $verify = $user->verify;
        
        if (!$profil) {
            return redirect()->route('mitra.profil-edit');
        }

        // Pastikan nama file view sesuai dengan yang kamu pakai (mitra.profil atau mitra.profil-edit)
        return view('mitra.profil-edit', compact('user', 'profil', 'verify'));
    }
}