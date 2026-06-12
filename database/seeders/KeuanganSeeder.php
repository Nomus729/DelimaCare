<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResepMedis;
use App\Models\ResepMedisItem;
use App\Models\RekamMedis;
use App\Models\Medicine;
use App\Models\Doctor;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class KeuanganSeeder extends Seeder
{
    public function run(): void
    {
        $rekamMedisList = RekamMedis::all();
        $medicines = Medicine::all();
        $doctors = Doctor::all();
        
        if ($rekamMedisList->isEmpty() || $medicines->isEmpty()) {
            $this->command->warn('No RekamMedis or Medicines found. Please make sure they are seeded first.');
            return;
        }

        $this->command->info('Creating Resep Medis and Items for the past 6 months...');

        // Clear existing data to avoid clutter if run multiple times
        ResepMedisItem::query()->delete();
        ResepMedis::query()->delete();
        Pengeluaran::query()->delete();

        // Create random Resep Medis over the last 6 months
        for ($i = 0; $i < 60; $i++) {
            $rm = $rekamMedisList->random();
            $doctor = $doctors->count() > 0 ? $doctors->random()->name : 'Dr. Budi';
            
            // Random date within the last 6 months
            $date = Carbon::now()->subDays(rand(0, 180));

            $resep = ResepMedis::create([
                'no_resep' => 'RSP-' . strtoupper(uniqid()),
                'rekam_medis_id' => $rm->id,
                'nama_pasien' => $rm->nama_pasien,
                'dokter_pemeriksa' => $doctor,
                'tanggal_resep' => $date,
                'biaya_dokter' => rand(5, 15) * 10000, // Random doctor fee (50k - 150k)
                'status' => 'Selesai',
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Create 1-4 items per resep
            $numItems = rand(1, 4);
            $selectedMeds = $medicines->random($numItems);
            
            foreach ($selectedMeds as $med) {
                ResepMedisItem::create([
                    'resep_medis_id' => $resep->id,
                    'medicine_id' => $med->id,
                    'nama_obat' => $med->name,
                    'satuan' => $med->unit ?? 'Tablet',
                    'jumlah' => rand(1, 5),
                    'aturan_pakai' => '3x1 sesudah makan',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
        
        $this->command->info('Creating Pengeluaran for the past 6 months...');
        
        // Create random Pengeluaran
        $kategori = ['Operasional', 'Gaji Pegawai', 'Pembelian Alat', 'Lainnya'];
        $judul = ['Beli ATK', 'Bayar Listrik', 'Gaji Perawat', 'Beli Timbangan', 'Biaya Kebersihan', 'Internet Bulanan'];
        
        for ($i = 0; $i < 20; $i++) {
            $date = Carbon::now()->subDays(rand(0, 180));
            Pengeluaran::create([
                'judul' => $judul[array_rand($judul)],
                'kategori' => $kategori[array_rand($kategori)],
                'nominal' => rand(10, 100) * 50000,
                'tanggal' => $date,
                'keterangan' => 'Pengeluaran otomatis untuk data testing.',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        $this->command->info('KeuanganSeeder completed successfully.');
    }
}
