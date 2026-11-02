<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // Menampilkan halaman form
    public function index()
    {
        // Ambil data pertama dari tabel setting (karena pengaturannya cuma 1)
        $setting = Setting::first(); 
        
        // Kembalikan ke view blade pengaturan-global yang tadi kita buat
        return view('admin.pengaturan-global', compact('setting'));
    }

    // Memproses update data dan upload logo
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // Cari data setting, kalau belum ada sama sekali di database, buat baru
        $setting = Setting::first() ?? new Setting();
        
        $setting->app_name = $request->app_name;

        // JEDER! Logika Upload Gambar
        if ($request->hasFile('app_logo')) {
            // Hapus logo lama dari folder storage jika ada
            if ($setting->app_logo && Storage::disk('public')->exists($setting->app_logo)) {
                Storage::disk('public')->delete($setting->app_logo);
            }

            // Simpan logo baru ke folder storage/app/public/logos
            $path = $request->file('app_logo')->store('logos', 'public');
            $setting->app_logo = $path;
        }

        $setting->save();

        return back()->with('success', 'Jeder! Pengaturan Global dan Logo berhasil diperbarui! 🚀');
    }
}