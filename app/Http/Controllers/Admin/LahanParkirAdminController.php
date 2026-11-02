<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LahanParkirService;
use Illuminate\Http\Request;

class LahanParkirAdminController extends Controller
{
    protected $parkirService;

    public function __construct(LahanParkirService $parkirService)
    {
        $this->parkirService = $parkirService;
    }

    public function index()
    {
        $lahan_parkirs = $this->parkirService->listSemuaParkir();
        return view('admin.lahan-parkir', compact('lahan_parkirs'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        $this->parkirService->verifikasiParkir($id, $request->status);

        $pesan = $request->status === 'disetujui' ? 'Lahan Parkir berhasil disetujui!' : 'Pengajuan Lahan Parkir ditolak.';
        return redirect()->back()->with('success', $pesan);
    }
}