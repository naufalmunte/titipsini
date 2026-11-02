<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verify;
use Illuminate\Http\Request;

class VerifyAdminController extends Controller
{
    public function index()
    {
        // Ambil data yang pending di atas, yang lain di bawah
        $verifications = Verify::with('user')->orderBy('status', 'asc')->latest()->get();
        return view('admin.verify', compact('verifications'));
    }

    public function updateStatus(Request $request, $id)
{
    $verification = \App\Models\Verify::findOrFail($id);
    $statusBaru = $request->status; // 'approved', 'rejected', atau 'pending'

    \DB::beginTransaction();
    try {
        // 1. Update status di tabel 'verifies'
        $verification->update([
            'status' => $statusBaru,
            'catatan' => $request->catatan ?? null
        ]);

        // 2. JEDER! Update kolom 'verify' di tabel 'users' jika statusnya 'approved'
        $user = \App\Models\User::find($verification->user_id);
        if ($user) {
            $user->update([
                // Jika approved jadikan 1, jika tidak jadikan 0 (terkunci lagi)
                'verify' => ($statusBaru === 'approved') ? 1 : 0
            ]);
        }

        \DB::commit();
        return redirect()->back()->with('success', 'Status berhasil diupdate, Raja! Akses user otomatis menyesuaikan.');

    } catch (\Exception $e) {
        \DB::rollBack();
        return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
    }
}
}