<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:auto-cancel-orders')]
#[Description('Command description')]
class AutoCancelOrders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
{
    // Cari pesanan yang tanggal_booking-nya sudah lewat dari hari ini
    // DAN statusnya masih menunggu konfirmasi admin
    $cancelledOrders = Order::where('status', 'menunggu_konfirmasi')
        ->where('tanggal_booking', '<', now()->toDateString())
        ->get();

    foreach ($cancelledOrders as $order) {
        $order->update(['status' => 'dibatalkan']);
        // Optional: Tambahkan notifikasi ke pelanggan
    }

    $this->info('Auto-cancel berhasil dijalankan. Jumlah: ' . $cancelledOrders->count());
}
}
