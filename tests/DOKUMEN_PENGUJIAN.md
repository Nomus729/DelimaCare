# DOKUMEN PENGUJIAN KOMPREHENSIF - DELIMACARE
**Peran**: Senior QA Engineer & Lead Developer Laravel
**Target Proyek**: DelimaCare (Laravel 10, PHP 8.1, PHPUnit 10)

Dokumen ini disusun untuk memberikan panduan pengujian menyeluruh terhadap sistem informasi klinik DelimaCare. Pengujian dibagi menjadi 3 aspek utama sesuai permintaan:
1. Skenario Uji Black Box (Fungsionalitas & Hak Akses)
2. Struktur Matriks Unit Testing / Feature Testing (Laravel Test Case)
3. Alur Implementasi Test-Driven Development (TDD) untuk fitur Live Chat Pasien

---

## 1. SKENARIO BLACK BOX TESTING

Berikut adalah tabel skenario pengujian fungsionalitas dan hak akses (authorization) berdasarkan struktur rute `web.php` DelimaCare:

| ID Test | Fitur/Fungsi | Kondisi Input / Aksi | Hasil yang Diharapkan (Success & Fail Case) | Jenis Hak Akses |
| :--- | :--- | :--- | :--- | :--- |
| **AUTH-01** | Pencarian Artikel | Melakukan pencarian menggunakan parameter query `search` (Contoh: `/artikel?search=gizi`) | **Success**: Halaman menampilkan artikel yang mengandung kata "gizi".<br>**Fail**: Menampilkan halaman kosong dengan pesan bahwa artikel tidak ditemukan jika mencari kata kunci acak. | Guest / Patient / Admin |
| **AUTH-02** | Filter Kategori Artikel | Mengakses artikel dengan parameter filter `category` (Contoh: `/artikel?category=tips`) | **Success**: Hanya menampilkan artikel dengan kategori "tips".<br>**Fail**: Kategori kosong atau tidak ada artikel di kategori tersebut menampilkan state kosong. | Guest / Patient / Admin |
| **AUTH-03** | Pagination Artikel | Mengklik halaman berikutnya pada navigasi pagination artikel | **Success**: URL berubah menjadi `/artikel?page=2` dengan data artikel berikutnya (maksimal 9 per halaman). | Guest / Patient / Admin |
| **AUTH-04** | Detail Artikel (Slug) | Mengakses rute `/artikel/{slug}` dengan slug valid dan tidak valid | **Success**: Menampilkan konten artikel secara detail dan rekomendasi 2 artikel terkait.<br>**Fail**: Menghasilkan respon HTTP 404 Not Found jika slug tidak ada. | Guest / Patient / Admin |
| **AUTH-05** | Form Registrasi | Mengirim POST ke `/register` dengan data user baru | **Success**: Akun tersimpan di DB, dialihkan ke halaman login/dashboard.<br>**Fail**: Validasi gagal jika email sudah terdaftar, password kurang dari 8 karakter, atau konfirmasi password tidak cocok. | Guest |
| **AUTH-06** | Login Pengguna | Mengirim POST ke `/login` dengan kredensial user | **Success**: Berhasil login, session dibuat, dialihkan ke rute `/portal` (jika pasien) atau `/admin` (jika admin).<br>**Fail**: Gagal login dengan pesan error jika password salah atau email/username tidak terdaftar. | Guest |
| **AUTH-07** | Request OTP Lupa Password | Mengirim POST ke `/forgot-password` dengan email terdaftar dan tidak terdaftar | **Success**: OTP 6-digit dikirim via email, data masuk ke `password_reset_tokens`, dialihkan ke form reset.<br>**Fail**: Error validasi jika email kosong atau tidak terdaftar di database. | Guest |
| **AUTH-08** | Verifikasi OTP & Reset | Mengirim POST ke `/reset-password` berisi email, kode OTP 6-digit, dan password baru | **Success**: OTP terverifikasi, password diperbarui di DB, OTP dihapus dari DB, dialihkan ke login dengan pesan sukses.<br>**Fail**: Gagal jika kode OTP salah/kadaluarsa atau password baru tidak memenuhi syarat. | Guest |
| **AUTH-09** | Proteksi Rute Logout | Mengirim POST ke `/logout` | **Success**: Pengguna keluar, session dihapus, dialihkan ke halaman utama.<br>**Fail**: Akses langsung (tanpa login) ditolak oleh middleware `auth` dan dialihkan ke login. | Patient / Admin |
| **AUTH-10** | Middleware 'patient' | Mengakses rute `/portal` | **Success**: Pasien terautentikasi berhasil masuk ke portal.<br>**Fail**: User non-pasien (Admin/Guest) diblokir (HTTP 403 atau dialihkan ke login). | Patient |
| **AUTH-11** | Middleware 'admin' | Mengakses rute `/admin/*` | **Success**: Admin terautentikasi berhasil masuk ke dashboard admin.<br>**Fail**: User non-admin (Patient/Guest) diblokir (HTTP 403 atau dialihkan ke login). | Admin |
| **PORT-01** | Halaman Portal Pasien | Mengakses GET `/portal` | **Success**: Menampilkan jadwal reservasi pribadi pasien, daftar dokter, rekam medis pribadi beserta relasi item resep obat. | Patient |
| **PORT-02** | Update Profil Pasien | Mengirim PUT ke `/portal/profil/update` dengan data baru | **Success**: Profil pasien (username/email/detail) terupdate di DB.<br>**Fail**: Error jika email sudah digunakan oleh user lain. | Patient |
| **PORT-03** | Reservasi Pasien Baru | Mengirim POST ke `/portal/reservasi` dengan data dokter, tanggal, telepon, layanan | **Success**: Data tersimpan di DB dengan status 'Menunggu', nomor antrean dibuat berurutan, dialihkan kembali ke portal.<br>**Fail**: Error jika dokter tidak ada, nomor telepon kosong, atau format input tidak sesuai. | Patient |
| **PORT-04** | Batal Reservasi Pasien | Mengirim DELETE ke `/portal/reservasi/{id}` | **Success**: Reservasi terhapus dari database, dialihkan ke portal.<br>**Fail**: Menolak pembatalan jika pasien mencoba menghapus reservasi milik pasien lain (HTTP 403). | Patient |
| **PORT-05** | Load Chat Pasien | Mengakses GET `/portal/chat/load` via AJAX | **Success**: Mengembalikan riwayat chat konsultasi pasien dalam format JSON. | Patient |
| **PORT-06** | Send Chat Pasien | Mengirim POST ke `/portal/chat/send` via AJAX berisi pesan | **Success**: Pesan tersimpan di DB, event broadcast `MessageSent` dipicu, respon JSON sukses.<br>**Fail**: Validasi gagal jika pesan kosong. | Patient |
| **ADM-01** | AJAX Partial Endpoints | Mengakses endpoint partial GET (Contoh: `/admin/dashboard/partial`, `/admin/konten/partial`, dsb.) | **Success**: Mengembalikan potongan HTML partial atau data statistik polling dalam format JSON.<br>**Fail**: Akses non-admin menghasilkan status HTTP 403. | Admin |
| **ADM-02** | CRUD Konten/Artikel | Melakukan aksi POST, PUT, DELETE pada resource `admin.konten` | **Success**: Artikel berhasil ditambah/diedit/dihapus, slug tergenerasi otomatis.<br>**Fail**: Judul kosong atau slug duplikat ditolak validasi. | Admin |
| **ADM-03** | CRUD Obat (Medicines) | Melakukan CRUD pada resource `admin.medicines` | **Success**: Obat tersimpan/diperbarui/dihapus dari inventori obat.<br>**Fail**: Kode obat duplikat atau stok bernilai negatif gagal divalidasi. | Admin |
| **ADM-04** | CRUD Dokter | Melakukan CRUD pada resource `admin.doctors` | **Success**: Data dokter terdaftar beserta jadwal prakteknya.<br>**Fail**: Spesialisasi kosong atau nama dokter kosong ditolak. | Admin |
| **ADM-05** | CRUD Rekam Medis | Melakukan CRUD pada resource `admin.rekam-medis` | **Success**: Rekam medis pasien berhasil disimpan/diubah.<br>**Fail**: ID user pasien tidak valid atau keluhan utama kosong. | Admin |
| **ADM-06** | Simpan Resep Baru | Mengirim POST ke `/admin/resep-medis` dengan obat dan jumlah | **Success**: Resep medis dibuat dan terhubung ke rekam medis.<br>**Fail**: Obat tidak ditemukan atau jumlah melebihi stok yang tersedia. | Admin |
| **ADM-07** | Update Status Resep | Mengirim PATCH ke `/admin/resep-medis/{id}/status` | **Success**: Status resep (misal: 'Selesai') diperbarui di database. | Admin |
| **ADM-08** | Hapus Resep Medis | Mengirim DELETE ke `/admin/resep-medis/{id}` | **Success**: Resep medis terhapus dari database dan stok obat dikembalikan. | Admin |
| **ADM-09** | API Search Medicine | Mengakses GET `/admin/api/medicines/search?q={keyword}` | **Success**: Mengembalikan daftar obat yang cocok dengan kata kunci dalam format JSON. | Admin |
| **ADM-10** | Simpan Reservasi Admin | Mengirim POST ke `/admin/reservasi/store` secara manual | **Success**: Reservasi manual atas nama pasien dibuat dengan status langsung 'Dikonfirmasi'. | Admin |
| **ADM-11** | Konfirmasi Reservasi | Mengirim PATCH ke `/admin/reservasi/{id}/konfirmasi` | **Success**: Mengubah status reservasi pasien dari 'Menunggu' menjadi 'Dikonfirmasi'. | Admin |
| **ADM-12** | Ubah Status Reservasi | Mengirim PATCH ke `/admin/reservasi/{id}/status` dengan status baru | **Success**: Status reservasi diperbarui (contoh: 'Selesai'). | Admin |
| **ADM-13** | Batal Reservasi Admin | Mengirim DELETE ke `/admin/reservasi/{id}/batal` | **Success**: Reservasi dibatalkan dari sistem oleh admin. | Admin |
| **ADM-14** | Live Chat Admin Users | Mengakses GET `/admin/chat/users` | **Success**: Mengembalikan daftar user yang pernah melakukan chat beserta info pesan terakhir dan jumlah pesan belum dibaca. | Admin |
| **ADM-15** | Live Chat Admin Messages | Mengakses GET `/admin/chat/{userId}` | **Success**: Mengembalikan history chat user tersebut dan menandai pesan user sebelumnya sebagai 'read'. | Admin |
| **ADM-16** | Live Chat Admin Send | Mengirim POST ke `/admin/chat/send` dengan payload user_id dan pesan | **Success**: Pesan disimpan ke DB dengan sender 'admin', dipicu broadcast event. | Admin |
| **ADM-17** | Kelola Keuangan | Mengirim POST/DELETE ke `/admin/pengeluaran` | **Success**: Data pengeluaran keuangan klinik bertambah atau terhapus di database. | Admin |
| **ADM-18** | Analytics & Laporan | Mengakses GET `/admin/report/stats` | **Success**: Mengembalikan data analitik keuangan dan statistik kunjungan bulanan (format JSON) untuk divisualisasikan. | Admin |

---

## 2. MATRIKS FEATURE/UNIT TESTING LARAVEL (PHPUnit)

Berikut adalah matriks pengujian dalam bentuk kerangka berkas pengujian Laravel Test Case (PHPUnit 10). Kode ini dirancang terstruktur dan siap digunakan di folder `tests/Feature/`.

### A. Modul Pasien (Portal Pasien & Public Access)
Berkas Uji: `tests/Feature/PatientPortalTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Reservasi;
use App\Models\Article;
use App\Models\RekamMedis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $patient;
    private Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup User Pasien
        $this->patient = User::create([
            'username' => 'budi_pasien',
            'email'    => 'budi@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        // Setup Dokter
        $this->doctor = Doctor::create([
            'nama'           => 'Dr. Andi',
            'spesialisasi'   => 'Anak',
            'jadwal_praktek' => 'Senin - Jumat (09:00 - 15:00)',
            'status'         => 'Tersedia',
        ]);
        
        $this->doctor->schedules()->create([
            'day_of_week' => 'Senin',
            'start_time'  => '09:00:00',
            'end_time'    => '15:00:00',
        ]);
    }

    /** @test */
    public function test_guest_can_search_and_filter_articles_with_pagination()
    {
        // Setup dummy articles
        Article::create([
            'title' => 'Tips Sehat Flu',
            'slug' => 'tips-sehat-flu',
            'category' => 'tips',
            'content' => 'Ini tips menghindari flu saat musim pancaroba.'
        ]);

        // Uji pencarian
        $response = $this->get(route('articles.index', ['search' => 'Flu']));
        $response->assertStatus(200);
        $response->assertSee('Tips Sehat Flu');

        // Uji filter kategori
        $responseCategory = $this->get(route('articles.index', ['category' => 'tips']));
        $responseCategory->assertStatus(200);
        $responseCategory->assertSee('Tips Sehat Flu');
    }

    /** @test */
    public function test_guest_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'username' => 'pasien_baru',
            'email' => 'baru@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'username' => 'pasien_baru',
            'email' => 'baru@test.com',
            'role' => 'pasien'
        ]);
    }

    /** @test */
    public function test_patient_can_access_portal_with_medical_records()
    {
        // Buat rekam medis terkait pasien
        RekamMedis::create([
            'user_id' => $this->patient->id,
            'nama_pasien' => $this->patient->username,
            'keluhan' => 'Sakit kepala hebat',
            'diagnosa' => 'Migrain',
            'tanggal_periksa' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->patient)->get(route('portal'));
        $response->assertStatus(200);
        $response->assertSee('Sakit kepala hebat');
        $response->assertSee('Migrain');
    }

    /** @test */
    public function test_patient_can_create_new_reservation()
    {
        $response = $this->actingAs($this->patient)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Konsultasi Anak',
            'tanggal'   => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('portal'));
        $this->assertDatabaseHas('reservasi', [
            'user_id'   => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'status'    => 'Menunggu'
        ]);
    }

    /** @test */
    public function test_patient_cannot_cancel_other_patients_reservation()
    {
        // Buat user pasien lain
        $otherPatient = User::create([
            'username' => 'other_patient',
            'email'    => 'other@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        // Buat reservasi milik user pasien lain
        $reservasi = Reservasi::create([
            'user_id' => $otherPatient->id,
            'nama' => 'other_patient',
            'phone' => '0811111111',
            'layanan' => 'Umum',
            'doctor_id' => $this->doctor->id,
            'tanggal' => now()->format('Y-m-d'),
            'waktu' => '08:00',
            'queue_number' => 1,
            'estimated_time' => '08:00',
            'status' => 'Menunggu',
        ]);

        // Login sebagai pasien pertama, coba delete reservasi milik pasien kedua
        $response = $this->actingAs($this->patient)
            ->delete(route('reservasi.destroy', $reservasi->id));

        // Harus gagal/ditolak (HTTP 403 atau redirect kembali dengan error)
        $response->assertStatus(403);
        $this->assertDatabaseHas('reservasi', ['id' => $reservasi->id]);
    }

    /** @test */
    public function test_patient_can_load_chat_history()
    {
        $response = $this->actingAs($this->patient)->get(route('chat.load'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['messages']);
    }
}
```

### B. Modul Admin (Admin Panel & Dokter)
Berkas Uji: `tests/Feature/AdminPanelTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Reservasi;
use App\Models\Medicine;
use App\Models\RekamMedis;
use App\Models\Pengeluaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup User Admin
        $this->admin = User::create([
            'username' => 'admin_utama',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Setup Dokter
        $this->doctor = Doctor::create([
            'nama'           => 'Dr. Hartono',
            'spesialisasi'   => 'Kandungan',
            'jadwal_praktek' => 'Senin - Rabu (10:00 - 14:00)',
            'status'         => 'Tersedia',
        ]);
    }

    /** @test */
    public function test_non_admin_cannot_access_admin_dashboard_partials()
    {
        $patient = User::create([
            'username' => 'pasien_biasa',
            'email'    => 'pasien_biasa@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        // Coba akses lazy loading partial endpoint admin
        $response = $this->actingAs($patient)->get(route('admin.dashboard.partial'));
        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function test_admin_can_access_dashboard_lazy_loading_partials()
    {
        $endpoints = [
            'admin.dashboard.partial',
            'admin.konten.partial',
            'admin.inventori.partial',
            'admin.keuangan.partial',
            'admin.laporan.partial',
            'admin.doctors.partial',
            'admin.reservasi.partial',
            'admin.rekam-medis.partial',
            'admin.konsultasi.partial',
            'admin.stats.polling'
        ];

        foreach ($endpoints as $route) {
            $response = $this->actingAs($this->admin)->get(route($route));
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function test_admin_can_manage_medicines_crud()
    {
        // 1. Create Medicine
        $response = $this->actingAs($this->admin)->post(route('admin.medicines.store'), [
            'name' => 'Paracetamol 500mg',
            'category' => 'Analgesik',
            'stock' => 100,
            'unit' => 'Tablet',
            'purchase_price' => 500,
            'selling_price' => 1000
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('medicines', ['name' => 'Paracetamol 500mg']);

        $medicine = Medicine::where('name', 'Paracetamol 500mg')->first();

        // 2. Update Medicine
        $responseUpdate = $this->actingAs($this->admin)->put(route('admin.medicines.update', $medicine->id), [
            'name' => 'Paracetamol 500mg Gold',
            'category' => 'Analgesik',
            'stock' => 120,
            'unit' => 'Tablet',
            'purchase_price' => 600,
            'selling_price' => 1200
        ]);
        $responseUpdate->assertRedirect();
        $this->assertDatabaseHas('medicines', ['name' => 'Paracetamol 500mg Gold']);

        // 3. Delete Medicine
        $responseDelete = $this->actingAs($this->admin)->delete(route('admin.medicines.destroy', $medicine->id));
        $responseDelete->assertRedirect();
        $this->assertDatabaseMissing('medicines', ['id' => $medicine->id]);
    }

    /** @test */
    public function test_admin_can_search_medicine_via_api()
    {
        Medicine::create([
            'name' => 'Amoxicillin 500mg',
            'category' => 'Antibiotik',
            'stock' => 50,
            'unit' => 'Tablet',
            'purchase_price' => 1000,
            'selling_price' => 2000
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.api.medicines.search', ['q' => 'Amox']));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Amoxicillin 500mg']);
    }

    /** @test */
    public function test_admin_can_confirm_and_update_status_reservasi()
    {
        $reservasi = Reservasi::create([
            'nama' => 'Pasien Rujukan',
            'phone' => '0812345',
            'layanan' => 'Kandungan',
            'doctor_id' => $this->doctor->id,
            'tanggal' => now()->format('Y-m-d'),
            'waktu' => '10:00',
            'queue_number' => 1,
            'estimated_time' => '10:00',
            'status' => 'Menunggu',
        ]);

        // Konfirmasi Reservasi
        $responseConfirm = $this->actingAs($this->admin)
            ->patch(route('admin.reservasi.konfirmasi', $reservasi->id));
        $responseConfirm->assertRedirect();
        $this->assertEquals('Dikonfirmasi', $reservasi->fresh()->status);

        // Ubah Status Reservasi ke Selesai
        $responseStatus = $this->actingAs($this->admin)
            ->patch(route('admin.reservasi.status', $reservasi->id), [
                'status' => 'Selesai'
            ]);
        $responseStatus->assertRedirect();
        $this->assertEquals('Selesai', $reservasi->fresh()->status);
    }

    /** @test */
    public function test_admin_can_load_active_chat_users()
    {
        $response = $this->actingAs($this->admin)->get('/admin/chat/users');
        $response->assertStatus(200);
        $response->assertJsonStructure(['users']);
    }

    /** @test */
    public function test_admin_can_manage_expenses()
    {
        // 1. Simpan Pengeluaran
        $response = $this->actingAs($this->admin)->post(route('admin.pengeluaran.store'), [
            'deskripsi' => 'Beli Tissue Klinik',
            'jumlah' => 150000,
            'tanggal' => now()->format('Y-m-d')
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('pengeluaran', ['deskripsi' => 'Beli Tissue Klinik']);

        $pengeluaran = Pengeluaran::where('deskripsi', 'Beli Tissue Klinik')->first();

        // 2. Hapus Pengeluaran
        $responseDelete = $this->actingAs($this->admin)->delete(route('admin.pengeluaran.destroy', $pengeluaran->id));
        $responseDelete->assertRedirect();
        $this->assertDatabaseMissing('pengeluaran', ['id' => $pengeluaran->id]);
    }
}
```

---

## 3. SKENARIO PENERAPAN TDD (Test-Driven Development)

TDD dijalankan dengan siklus **Red-Green-Refactor**. Berikut adalah implementasi konkret untuk kasus: **Live Chat Pasien (Pesan kosong harus gagal divalidasi, pesan valid harus tersimpan dan memicu event Pusher/Broadcast).**

### SIKLUS 1: RED (Menulis Tes Terlebih Dahulu & Mengonfirmasi Kegagalan)

Sebelum mengubah logika di `ConsultationController`, buatlah berkas pengujian berikut di `tests/Feature/PatientLiveChatTest.php`.

#### Berkas Uji: `tests/Feature/PatientLiveChatTest.php`
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ConsultationMessage;
use App\Events\MessageSent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PatientLiveChatTest extends TestCase
{
    use RefreshDatabase;

    private User $patient;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user pasien
        $this->patient = User::create([
            'username' => 'anton_chat',
            'email'    => 'anton@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);
    }

    /** @test */
    public function test_sending_empty_chat_message_should_fail_validation()
    {
        // Kirim request chat kosong
        $response = $this->actingAs($this->patient)
            ->postJson(route('chat.send'), [
                'message' => '', // Kosong
            ]);

        // Verifikasi status respon harus 422 Unprocessable Entity karena validasi gagal
        $response->assertStatus(422);
        
        // Pastikan ada pesan error validasi untuk key 'message'
        $response->assertJsonValidationErrors('message');

        // Pastikan tidak ada data baru yang masuk ke database
        $this->assertDatabaseEmpty('consultation_messages');
    }

    /** @test */
    public function test_sending_valid_chat_message_saves_to_db_and_triggers_pusher_broadcast()
    {
        // Fake events untuk mencegah broadcast asli dikirim ke Pusher selama testing
        Event::fake([
            MessageSent::class
        ]);

        // Kirim request chat valid
        $response = $this->actingAs($this->patient)
            ->postJson(route('chat.send'), [
                'message' => 'Halo Dokter, saya butuh konsultasi.',
            ]);

        // Verifikasi status respon sukses 200/201
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        // Pastikan data pesan tersimpan di database
        $this->assertDatabaseHas('consultation_messages', [
            'username' => 'anton_chat',
            'sender'   => 'user',
            'message'  => 'Halo Dokter, saya butuh konsultasi.',
        ]);

        // Pastikan event MessageSent di-broadcast
        Event::assertDispatched(MessageSent::class, function ($event) {
            return $event->message->message === 'Halo Dokter, saya butuh konsultasi.' 
                && $event->message->username === 'anton_chat';
        });
    }
}
```

#### Hasil Uji (Red Phase)
Saat Anda menjalankan pengujian ini menggunakan perintah:
`php artisan test tests/Feature/PatientLiveChatTest.php`

Maka hasil pengujian akan **Gagal (RED)** karena:
1. `test_sending_empty_chat_message_should_fail_validation` -> Gagal dengan status 200 karena controller belum melakukan validasi dan langsung menyimpan pesan kosong ke database.
2. `test_sending_valid_chat_message_saves_to_db_and_triggers_pusher_broadcast` -> Gagal jika `MessageSent` class belum di-import atau didefinisikan dengan benar.

---

### SIKLUS 2: GREEN (Menulis Kode Fungsional untuk Meloloskan Tes)

Untuk mengubah status tes menjadi hijau (**GREEN**), kita harus memodifikasi controller `ConsultationController.php` pada method `sendMessage` agar menerapkan aturan validasi input.

#### Modifikasi File: `app/Http/Controllers/ConsultationController.php`
```php
<?php

namespace App\Http\Controllers;

use App\Models\ConsultationMessage;
use App\Events\MessageSent; // Pastikan Event di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    // ... loadMessages() tetap sama ...

    public function sendMessage(Request $request)
    {
        // 1. Tambahkan Validasi TDD
        $validatedData = $request->validate([
            'message' => 'required|string|min:1',
        ]);

        // 2. Simpan Data Ke Database
        $message = ConsultationMessage::create([
            'username' => Auth::user()->username,
            'sender' => $request->sender ?? 'user',
            'type' => $request->type ?? 'text',
            'message' => $validatedData['message'], // Gunakan data hasil validasi
        ]);

        // 3. Broadcast Event ke Pusher
        broadcast(new MessageSent($message))->toOthers();

        // 4. Return Response Sukses
        return response()->json(['success' => true, 'message' => $message]);
    }
    
    // ... Sisi Admin tetap sama ...
}
```

#### Hasil Uji (Green Phase)
Jalankan kembali perintah pengujian:
`php artisan test tests/Feature/PatientLiveChatTest.php`

**Hasil**: Seluruh pengujian lolos (**PASSED / GREEN**). 
Sistem sekarang memvalidasi input kosong dengan status `422`, memblokir penyimpanan data yang tidak valid, serta memicu broadcast event Pusher dengan benar saat pesan valid dikirim.

---

### SIKLUS 3: REFACTOR (Membersihkan & Mengoptimalkan Kode)

Setelah kode kita terbukti bekerja dengan benar (Green), kita dapat melakukan perbaikan kode tanpa khawatir merusak fungsionalitas. Beberapa langkah refactoring yang dapat dilakukan:

1. **Optimasi Respon Format**:
   Kita bisa memindahkan pembersihan input ke Form Request untuk menjaga Controller tetap ramping.
   Buat class request baru: `php artisan make:request SendChatRequest`
   
   ```php
   namespace App\Http\Requests;

   use Illuminate\Foundation\Http\FormRequest;

   class SendChatRequest extends FormRequest
   {
       public function authorize()
       {
           return true; // Autentikasi ditangani oleh Middleware patient
       }

       public function rules()
       {
           return [
               'message' => 'required|string|min:1|max:1000', // batasi panjang pesan
           ];
       }
   }
   ```

2. **Gunakan SendChatRequest di Controller**:
   ```php
   use App\Http\Requests\SendChatRequest;

   public function sendMessage(SendChatRequest $request)
   {
       $message = ConsultationMessage::create([
           'username' => Auth::user()->username,
           'sender'   => $request->sender ?? 'user',
           'type'     => $request->type ?? 'text',
           'message'  => $request->validated()['message'],
       ]);

       broadcast(new MessageSent($message))->toOthers();

       return response()->json(['success' => true, 'message' => $message]);
   }
   ```

3. **Verifikasi Ulang**:
   Jalankan kembali test suite: `php artisan test tests/Feature/PatientLiveChatTest.php`
   Semua test harus tetap berwarna hijau (**GREEN**), membuktikan refactoring berhasil tanpa mengganggu integritas program.
