<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Reservasi;
use App\Models\RekamMedis;
use App\Models\ResepMedis;
use App\Models\ResepMedisItem;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DelimaPremiumSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clean up existing transactional records (EXCEPT Users table)
        DB::statement('PRAGMA foreign_keys = OFF;');
        ResepMedisItem::truncate();
        ResepMedis::truncate();
        RekamMedis::truncate();
        Reservasi::truncate();
        Doctor::truncate();
        Medicine::truncate();
        Article::truncate();
        DB::statement('PRAGMA foreign_keys = ON;');

        // 2. Seed premium articles to maximize Content dashboard visuals
        $adminUser = User::where('role', 'admin')->first() ?? User::first();
        $authorId = $adminUser ? $adminUser->id : 1;

        $articles = [
            [
                'title' => 'Tips Menjaga Kesehatan Kehamilan di Trimester Pertama',
                'slug' => 'tips-menjaga-kesehatan-kehamilan-di-trimester-pertama',
                'category' => 'Artikel',
                'content' => 'Trimester pertama adalah masa krusial bagi perkembangan janin. Pada fase ini, organ-organ penting janin mulai terbentuk. Ibu hamil disarankan untuk mengonsumsi asam folat secara rutin, avoiding makanan mentah, dan meminimalkan stres fisik serta mental.',
                'image_path' => 'https://images.unsplash.com/photo-1516627145497-ae6968895b74?q=80&w=600&auto=format&fit=crop',
                'author_id' => $authorId,
            ],
            [
                'title' => 'Mengenal Jenis-Jenis KB Modern dan Efek Sampingnya',
                'slug' => 'mengenal-jenis-jenis-kb-modern-dan-efek-sampingnya',
                'category' => 'Artikel',
                'content' => 'Memilih alat kontrasepsi yang tepat sangat penting bagi kenyamanan pasangan. Ada berbagai pilihan mulai dari Pil KB, KB Suntik bulanan, Implan, hingga IUD Copper T. Konsultasikan dengan bidan atau dokter untuk mengetahui kecocokan hormon Anda.',
                'image_path' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop',
                'author_id' => $authorId,
            ],
            [
                'title' => 'Pentingnya Konsultasi Rutin untuk Ibu Hamil',
                'slug' => 'pentingnya-konsultasi-rutin-untuk-ibu-hamil',
                'category' => 'Berita',
                'content' => 'Melakukan pemeriksaan rutin ke dokter kandungan atau bidan dapat meminimalkan risiko komplikasi kehamilan. Rekomendasi kunjungan adalah minimal 1 kali di trimester pertama, 1 kali di trimester kedua, dan minimal 2 kali di trimester ketiga.',
                'image_path' => 'https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?q=80&w=600&auto=format&fit=crop',
                'author_id' => $authorId,
            ],
            [
                'title' => 'Nutrisi Penting yang Wajib Dikonsumsi Ibu Hamil',
                'slug' => 'nutrisi-penting-yang-wajib-dikonsumsi-ibu-hamil',
                'category' => 'Berita',
                'content' => 'Makanan sehat kaya protein, kalsium, zat besi, dan asam folat sangat krusial. Konsumsilah sayuran berdaun hijau, buah-buahan segar, susu rendah lemak, telur, dan ikan matang secara seimbang untuk menyokong tumbuh kembang optimal sang buah hati.',
                'image_path' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?q=80&w=600&auto=format&fit=crop',
                'author_id' => $authorId,
            ],
        ];

        foreach ($articles as $art) {
            Article::create($art);
        }

        // 3. Ensure default doctors exist
        $doctors = [
            ['nama' => 'dr. Nurhaliza, SpOG', 'spesialisasi' => 'Kandungan (Sp.OG)', 'status' => 'Tersedia', 'jadwal_praktek' => 'Senin - Jumat (08:00 - 14:00)'],
            ['nama' => 'dr. Siti Rahayu, SpOG', 'spesialisasi' => 'Kandungan (Sp.OG)', 'status' => 'Tersedia', 'jadwal_praktek' => 'Senin - Sabtu (14:00 - 20:00)'],
            ['nama' => 'dr. Rahmat Hidayat', 'spesialisasi' => 'Dokter Umum', 'status' => 'Tersedia', 'jadwal_praktek' => 'Setiap Hari (24 Jam)'],
        ];

        foreach ($doctors as $d) {
            Doctor::updateOrCreate(['nama' => $d['nama']], $d);
        }

        $allDocs = Doctor::all();

        // 4. Seed dynamic medicine stock
        $medicinesData = [
            ['name' => 'Asam Folat 400mg', 'brand' => 'PT Pharma Indo', 'category' => 'Vitamin', 'stock' => 150, 'unit' => 'tablet', 'price' => 1500, 'min_stock' => 20, 'expired_at' => now()->addDays(365)],
            ['name' => 'IUD Copper T', 'brand' => 'PT Alkes Medika', 'category' => 'Alat KB', 'stock' => 45, 'unit' => 'pcs', 'price' => 185000, 'min_stock' => 10, 'expired_at' => now()->addDays(540)],
            ['name' => 'Amoxicillin 500mg', 'brand' => 'Kimia Farma', 'category' => 'Antibiotik', 'stock' => 250, 'unit' => 'tablet', 'price' => 3500, 'min_stock' => 30, 'expired_at' => now()->addDays(240)],
            ['name' => 'Paracetamol 500mg', 'brand' => 'Indofarma', 'category' => 'Analgesik', 'stock' => 300, 'unit' => 'tablet', 'price' => 2000, 'min_stock' => 40, 'expired_at' => now()->addDays(400)],
            ['name' => 'Asam Mefenamat 500mg', 'brand' => 'Dexa Medica', 'category' => 'Analgesik', 'stock' => 200, 'unit' => 'tablet', 'price' => 2500, 'min_stock' => 20, 'expired_at' => now()->addDays(300)],
            ['name' => 'Vitamin B Complex', 'brand' => 'Bio Farma', 'category' => 'Vitamin', 'stock' => 400, 'unit' => 'tablet', 'price' => 1000, 'min_stock' => 50, 'expired_at' => now()->addDays(600)],
            ['name' => 'Suplemen Zat Besi', 'brand' => 'Sanbe Farma', 'category' => 'Vitamin', 'stock' => 180, 'unit' => 'tablet', 'price' => 3000, 'min_stock' => 15, 'expired_at' => now()->addDays(450)],
        ];

        foreach ($medicinesData as $m) {
            Medicine::updateOrCreate(['name' => $m['name']], $m);
        }

        $allMeds = Medicine::all();

        // 5. Query all existing real patient accounts from user table
        $pasienUsers = User::where('role', 'pasien')->get();
        if ($pasienUsers->isEmpty()) {
            // Fallback if no pasien users are present
            $pasienUsers = collect([
                User::create([
                    'username' => 'Siti Aminah',
                    'email' => 'siti@delimacare.id',
                    'password' => bcrypt('password123'),
                    'role' => 'pasien'
                ])
            ]);
        }

        $categories = ['Kehamilan', 'Keluarga Berencana', 'Kontrol Umum', 'Konsultasi'];
        $complaints = [
            'Kehamilan' => 'Pemeriksaan kandungan rutin bulanan, cek denyut jantung janin.',
            'Keluarga Berencana' => 'Konsultasi KB hormonal dan pemasangan alat kontrasepsi.',
            'Kontrol Umum' => 'Cek tensi darah tinggi dan konsultasi pusing berulang.',
            'Konsultasi' => 'Pemeriksaan pasca melahirkan dan pemulihan tubuh.'
        ];

        $diagnoses = [
            'Kehamilan' => 'Kehamilan normal trimester sehat',
            'Keluarga Berencana' => 'Pemberian/pemasangan layanan KB sehat',
            'Kontrol Umum' => 'Pemulihan pasca kelelahan dan tensi stabil',
            'Konsultasi' => 'Post-partum checkup normal'
        ];

        $treatments = [
            'Kehamilan' => 'Pemberian vitamin kandungan & suplemen zat besi tambahan',
            'Keluarga Berencana' => 'Pemasangan IUD Copper T & penjelasan jadwal kontrol',
            'Kontrol Umum' => 'Pemberian antibiotik & obat pereda nyeri kepala',
            'Konsultasi' => 'Konseling nutrisi & pemberian suplemen pemulihan tubuh'
        ];

        $serialRM = 1;
        $serialRX = 1;

        // Loop through last 5 months chronologically: Jan, Feb, Mar, Apr, and May (excluding today)
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            
            // If it is this month, make sure we only seed BEFORE today (May 22)
            $isThisMonth = ($monthDate->month === now()->month && $monthDate->year === now()->year);
            $maxDay = $isThisMonth ? (now()->day - 1) : $monthDate->daysInMonth;
            
            if ($maxDay < 1) continue;

            // Generate 12 to 18 random visits for each month to build a highly realistic financial graph
            $visitCount = random_int(12, 18);

            for ($j = 0; $j < $visitCount; $j++) {
                $day = random_int(1, $maxDay);
                $visitDate = Carbon::create($monthDate->year, $monthDate->month, $day, random_int(8, 16), random_int(0, 59), 0);

                // Dynamically pick one of the REAL existing patient users from user table!
                $chosenUser = $pasienUsers->random();

                $patientName = $chosenUser->username;
                $phone = '0812' . random_int(10000000, 99999999);
                $category = $categories[array_rand($categories)];
                $doctor = $allDocs->random();

                // 5a. Create Reservasi (linked to real user ID)
                $reservasi = Reservasi::create([
                    'user_id' => $chosenUser->id,
                    'nama' => $patientName,
                    'phone' => $phone,
                    'layanan' => $category == 'Keluarga Berencana' ? 'Layanan KB' : $category,
                    'dokter_id' => $doctor->id,
                    'tanggal' => $visitDate->toDateString(),
                    'waktu' => '09:00 - 10:00',
                    'keluhan' => $complaints[$category] ?? 'Kontrol rutin kesehatan.',
                    'status' => 'Selesai',
                    'queue_number' => random_int(1, 15),
                    'created_at' => $visitDate,
                    'updated_at' => $visitDate,
                ]);

                // 5b. Create Rekam Medis (linked to real user ID)
                $noRM = 'RM-2026-' . str_pad($serialRM++, 4, '0', STR_PAD_LEFT);
                $rekamMedis = RekamMedis::create([
                    'user_id' => $chosenUser->id,
                    'reservasi_id' => $reservasi->id,
                    'no_rekam_medis' => $noRM,
                    'nama_pasien' => $patientName,
                    'usia' => random_int(21, 38),
                    'no_telepon' => $phone,
                    'alamat' => 'Jl. Melati No. ' . random_int(1, 99) . ', Bandung',
                    'golongan_darah' => ['A', 'B', 'AB', 'O'][random_int(0, 3)],
                    'kategori' => $category,
                    'tekanan_darah' => ['110/70', '115/75', '120/80', '125/80'][random_int(0, 3)],
                    'berat_badan' => random_int(52, 75) + (random_int(0, 9) / 10),
                    'tinggi_badan' => random_int(150, 165),
                    'catatan_medis' => $complaints[$category],
                    'diagnosis' => $diagnoses[$category],
                    'tindakan' => $treatments[$category],
                    'dokter_pemeriksa' => $doctor->nama,
                    'created_at' => $visitDate,
                    'updated_at' => $visitDate,
                ]);

                // 5c. Create Resep Medis (Invoicing)
                $noRX = 'RX-2026-' . str_pad($serialRX++, 4, '0', STR_PAD_LEFT);
                $resepMedis = ResepMedis::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'no_resep' => $noRX,
                    'nama_pasien' => $patientName,
                    'dokter_pemeriksa' => $doctor->nama,
                    'tanggal_resep' => $visitDate->toDateString(),
                    'catatan_apoteker' => 'Diminum sesudah makan secara teratur.',
                    'status' => 'Selesai',
                    'biaya_dokter' => [50000, 75000, 100000][random_int(0, 2)],
                    'created_at' => $visitDate,
                    'updated_at' => $visitDate,
                ]);

                // 5d. Create 1 to 3 dynamic ResepMedisItem entries to build apothecary sales revenue
                $itemCount = random_int(1, 3);
                $usedMeds = [];

                for ($k = 0; $k < $itemCount; $k++) {
                    $med = $allMeds->random();
                    if (in_array($med->id, $usedMeds)) continue;
                    $usedMeds[] = $med->id;

                    ResepMedisItem::create([
                        'resep_medis_id' => $resepMedis->id,
                        'medicine_id' => $med->id,
                        'nama_obat' => $med->name,
                        'satuan' => $med->unit,
                        'jumlah' => random_int(1, 10),
                        'aturan_pakai' => '3 x 1 sehari sesudah makan',
                        'created_at' => $visitDate,
                        'updated_at' => $visitDate,
                    ]);
                }
            }
        }
    }
}
