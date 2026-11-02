<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Services\PenjagaBangunanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjagaBangunanController extends Controller
{
    protected $penjagaService;

    // Inject Service
    public function __construct(PenjagaBangunanService $penjagaService)
    {
        $this->penjagaService = $penjagaService;
    }

    public function index()
    {
        $penjagas = $this->penjagaService->getPenjagaByMitra(Auth::id());
        return view('mitra.penjaga', compact('penjagas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penjaga' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'tgl_lahir' => 'required|date',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'detail_alamat' => 'required|string',
            'link_berkas' => 'required|url',
        ]);

        // Lempar ke Service
        $this->penjagaService->storePenjaga(Auth::id(), $validated);

        return redirect()->back()->with('success', 'Data Penjaga berhasil didaftarkan dan menunggu verifikasi Admin!');
    }

    public function update(Request $request, $id)
    {
        // Validasi inputan
        $request->validate([
            'nama_penjaga'  => 'required|string|max:255',
            'no_telp'       => 'required|string|max:20',
            'tgl_lahir'     => 'required|date',
            'link_berkas'   => 'required|url',
            'provinsi'      => 'required|string',
            'kota'          => 'required|string',
            'kecamatan'     => 'required|string',
            'detail_alamat' => 'required|string',
        ]);

        // Cari data penjaga berdasarkan ID
        $penjaga = \App\Models\Penjaga::findOrFail($id);

        // Update datanya
        $penjaga->update([
            'nama_penjaga'  => $request->nama_penjaga,
            'no_telp'       => $request->no_telp,
            'tgl_lahir'     => $request->tgl_lahir,
            'link_berkas'   => $request->link_berkas,
            'provinsi'      => $request->provinsi,
            'kota'          => $request->kota,
            'kecamatan'     => $request->kecamatan,
            'detail_alamat' => $request->detail_alamat,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data penjaga berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->penjagaService->deletePenjaga($id);
        return redirect()->back()->with('success', 'Data Penjaga berhasil dihapus.');
    }
}