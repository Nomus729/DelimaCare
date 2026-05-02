<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservasi;
use Carbon\Carbon;

class UpdateReservationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservasi:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis mengubah status reservasi menjadi Tidak Datang jika lewat 24 jam tanpa rekam medis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Cari reservasi yang tanggalnya sudah lewat lebih dari 24 jam
        // Dan statusnya masih 'Menunggu' atau 'Dikonfirmasi'
        $reservasi = Reservasi::whereIn('status', ['Menunggu', 'Dikonfirmasi'])
            ->where('tanggal', '<', Carbon::now()->subDay()->format('Y-m-d'))
            ->get();

        $count = 0;
        foreach ($reservasi as $item) {
            $item->status = 'Tidak Datang';
            $item->save();
            $count++;
        }

        $this->info("Berhasil memperbarui {$count} reservasi menjadi 'Tidak Datang'.");
    }
}
