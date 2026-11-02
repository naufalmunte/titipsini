<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Services\AlamatService;
use Illuminate\Http\Request;

class AlamatController extends Controller
{
    protected $alamatService;

    public function __construct(AlamatService $alamatService)
    {
        $this->alamatService = $alamatService;
    }

    public function index()
    {
        $alamats = $this->alamatService->getAlamatUser();
        return view('pelanggan.alamat', compact('alamats'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'nama_penerima' => 'required|string|max:255',
        'telepon'       => 'required|string|max:20',
        'provinsi'      => 'required|string|max:100',
        'kab_kota'      => 'required|string|max:100',
        'kecamatan'     => 'required|string|max:100',
        'alamat_lengkap'=> 'required|string',
        'catatan_alamat'=> 'nullable|string|max:255',
        'latitude'      => 'nullable',
        'longitude'     => 'nullable',
        'is_utama'      => 'nullable'
    ]);

    // Ubah is_utama ke boolean
    $validated['is_utama'] = $request->has('is_utama');

    // Simpan alamat melalui Service
    $alamat = $this->alamatService->simpanAlamat($validated);

    // --- LOGIKA JEDER DIMULAI DI SINI ---
    
    // Jika request datang dari AJAX (fetch di form Titip Bangunan)
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan!',
            'data'    => $alamat // Pastikan simpanAlamat() di Service mereturn object $alamat
        ]);
    }

    // Jika request datang dari form biasa (Halaman Kelola Alamat)
    return redirect()->route('pelanggan.alamat')
                     ->with('success', 'Alamat berhasil ditambahkan!');
}

// Tambahkan method ini di bawah method store()
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nama_penerima' => 'required|string|max:255',
        'telepon'       => 'required|string|max:20',
        'provinsi'      => 'required|string|max:100',
        'kab_kota'      => 'required|string|max:100',
        'kecamatan'     => 'required|string|max:100',
        'alamat_lengkap'=> 'required|string',
        'latitude'      => 'required',
        'longitude'     => 'required',
        'is_utama'      => 'nullable'
    ]);

    // Ubah is_utama ke boolean
    $validated['is_utama'] = $request->has('is_utama');
    
    // Kirim data ke Service untuk diproses
    // Pastikan method perbaruiAlamat sudah ada di AlamatService
    $this->alamatService->perbaruiAlamat($id, $validated);

    return redirect()->route('pelanggan.alamat')
                     ->with('success', 'Alamat berhasil diperbarui!');
}

public function destroy($id)
{
    try {
        $this->alamatService->hapusAlamat($id);
        return redirect()->back()->with('success', 'Alamat berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
}
}