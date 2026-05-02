<?php

namespace database\seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Doctor::create([
            'nama' => 'Dr. Siti Nurhaliza, Sp.OG',
            'spesialisasi' => 'Kebidanan & Kandungan',
            'status' => 'Tersedia',
            'jadwal_praktek' => 'Senin - Jumat (08:00 - 14:00)',
        ]);

        Doctor::create([
            'nama' => 'Dr. Dewi Lestari, Sp.OG',
            'spesialisasi' => 'Kebidanan & Kandungan',
            'status' => 'Tersedia',
            'jadwal_praktek' => 'Senin - Sabtu (14:00 - 20:00)',
        ]);

        Doctor::create([
            'nama' => 'Bidan Ani Wijaya',
            'spesialisasi' => 'Bidan',
            'status' => 'Istirahat',
            'jadwal_praktek' => 'Setiap Hari (24 Jam)',
        ]);
    }
}
