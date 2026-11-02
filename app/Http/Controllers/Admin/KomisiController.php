<?php

namespace App\Http\Controllers\Admin; // JEDER! Namespace Admin

use App\Http\Controllers\Controller;
use App\Models\KomisiSetting;
use Illuminate\Http\Request;

class KomisiController extends Controller
{
    public function index()
    {
        // Ambil data id 1, jika belum ada buat otomatis dengan nilai 65
        $setting = KomisiSetting::firstOrCreate(
            ['id' => 1],
            ['persen_mitra' => 65]
        );

        return view('admin.komisi', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'persen_mitra' => 'required|integer|min:0|max:100',
        ]);

        $setting = KomisiSetting::find(1);
        $setting->update([
            'persen_mitra' => $request->persen_mitra
        ]);

        return redirect()->back()->with('success', 'Persentase komisi berhasil diperbarui');
    }
}