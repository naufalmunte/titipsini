<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AlamatPelanggan; // Pastikan model ini sesuai dengan namamu
use App\Models\User;

class ProfilPelangganController extends Controller
{
    /**
     * Menampilkan halaman profil
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil alamat utama milik user yang login untuk ditampilkan di profil
        $profil = AlamatPelanggan::where('user_id', $user->id)
                                 ->where('is_utama', 1)
                                 ->first();

        // Lempar data ke view yang tadi kita buat
        return view('pelanggan.profil', compact('user', 'profil'));
    }

    /**
     * Menyimpan atau mengupdate data profil & alamat utama
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'telepon'        => 'required|string|max:20',
            'provinsi'       => 'required|string',
            'kab_kota'       => 'required|string',
            'kecamatan'      => 'required|string',
            'alamat_lengkap' => 'required|string',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
        ]);

        $user = Auth::user();

        // 2. Simpan nomor telepon ke tabel users (jika kamu punya kolom telepon di tabel users)
        // $user->update(['telepon' => $request->telepon]);

        // 3. Simpan koordinat dan alamat ke tabel alamat_pelanggans
        // updateOrCreate akan otomatis menimpa data jika is_utama = 1 sudah ada, 
        // atau membuat baru jika pelanggan belum pernah set alamat utama.
        AlamatPelanggan::updateOrCreate(
            [
                'user_id'  => $user->id,
                'is_utama' => 1 // Mengunci ini sebagai alamat utama (profil)
            ],
            [
                'nama_penerima'  => $user->name,
                'telepon'        => $request->telepon,
                'provinsi'       => $request->provinsi,
                'kab_kota'       => $request->kab_kota,
                'kecamatan'      => $request->kecamatan,
                'alamat_lengkap' => $request->alamat_lengkap,
                'latitude'       => $request->latitude,
                'longitude'      => $request->longitude,
            ]
        );

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Profil dan Lokasi Utama berhasil diperbarui!');
    }
}