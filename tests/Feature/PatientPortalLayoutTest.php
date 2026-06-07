<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientPortalLayoutTest extends TestCase
{
    use RefreshDatabase;

    private User $pasien;
    private Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat data pasien untuk simulasi login
        $this->pasien = User::create([
            'username' => 'pasien_tes',
            'email'    => 'pasien_tes@delimacare.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        // Buat data dokter dummy agar tab reservasi bisa dimuat tanpa error
        $this->doctor = Doctor::create([
            'nama'           => 'Dr. Tes Layout',
            'spesialisasi'   => 'Ibu & Anak',
            'jadwal_praktek' => 'Senin - Jumat (08:00 - 16:00)',
            'status'         => 'Tersedia',
        ]);
    }

    /** @test */
    public function portal_renders_with_mobile_navigation_and_all_tabs()
    {
        // 1. Lakukan request ke halaman portal dengan kondisi terautentikasi sebagai pasien
        $response = $this->actingAs($this->pasien)->get(route('portal'));

        // 2. Pastikan HTTP Response status code adalah 200 (OK)
        $response->assertStatus(200);

        // 3. Pastikan layout HTML utama menggunakan tinggi dinamis viewport (dvh) untuk mobile
        $response->assertSee('h-[100dvh]', false);

        // 4. Pastikan elemen Mobile Bottom Navigation (Navigasi Bawah) ter-render dengan kelas safe area pb-safe
        $response->assertSee('aria-label="Navigasi Bawah"', false);
        $response->assertSee('pb-safe', false);

        // 5. Pastikan navigasi desktop sidebar (Navigasi Utama) tetap ada
        $response->assertSee('aria-label="Navigasi Utama"', false);

        // 6. Pastikan tombol/tab navigasi memiliki trigger Alpine.js yang tepat untuk switchTab
        $response->assertSee('switchTab(\'reservasi\')', false);
        $response->assertSee('switchTab(\'jadwal\')', false);
        $response->assertSee('switchTab(\'rekam_medis\')', false);
        $response->assertSee('switchTab(\'konsultasi\')', false);

        // 7. Pastikan pembungkus tab menggunakan binding x-show Alpine.js
        $response->assertSee('x-show="activeTab === \'reservasi\'"', false);
        $response->assertSee('x-show="activeTab === \'jadwal\'"', false);
        $response->assertSee('x-show="activeTab === \'rekam_medis\'"', false);
        $response->assertSee('x-show="activeTab === \'konsultasi\'"', false);
    }

    /** @test */
    public function portal_reservasi_form_contains_necessary_attributes_for_tdd()
    {
        // 1. Request ke halaman portal
        $response = $this->actingAs($this->pasien)->get(route('portal'));

        $response->assertStatus(200);

        // 2. Pastikan form aksi mengarah ke endpoint yang benar
        $response->assertSee('action="' . route('reservasi.store') . '"', false);

        // 3. Proteksi TDD: Pastikan input kritis form reservasi tetap memiliki atribut 'id' dan 'name' yang utuh
        $response->assertSee('id="nama"', false);
        $response->assertSee('name="nama"', false);
        $response->assertSee('value="pasien_tes"', false); // Memastikan nama otomatis diset sesuai username login

        $response->assertSee('id="phone"', false);
        $response->assertSee('name="phone"', false);

        $response->assertSee('id="layanan"', false);
        $response->assertSee('name="layanan"', false);

        $response->assertSee('name="dokter_id"', false);

        $response->assertSee('id="tanggal"', false);
        $response->assertSee('name="tanggal"', false);

        $response->assertSee('id="keluhan"', false);
        $response->assertSee('name="keluhan"', false);
    }

    /** @test */
    public function portal_shows_success_popup_when_success_session_exists()
    {
        // 1. Request ke halaman portal dengan session success reservasi
        $successMessage = 'Reservasi Berhasil! No. Antrean Anda: 5 (Estimasi Jam 10:20)';
        $response = $this->actingAs($this->pasien)
            ->withSession(['success' => $successMessage])
            ->get(route('portal'));

        $response->assertStatus(200);

        // 2. Pastikan element popup sukses ter-render
        $response->assertSee('id="success-popup"', false);
        $response->assertSee('id="close-success-popup"', false);
        $response->assertSee('id="success-popup-message"', false);
        $response->assertSee($successMessage, false);
        $response->assertSee('Reservasi Berhasil!', false);
    }

    /** @test */
    public function portal_does_not_show_success_popup_when_success_session_does_not_exist()
    {
        // Request ke halaman portal tanpa session success
        $response = $this->actingAs($this->pasien)->get(route('portal'));
        $response->assertStatus(200);
        $response->assertDontSee('id="success-popup"', false);
    }

    /** @test */
    public function portal_does_not_display_doctors_on_leave_or_resting()
    {
        // 1. Create a doctor who is 'Tersedia' (available)
        $availableDoctor = Doctor::create([
            'nama'           => 'Dr. Dokter Tersedia',
            'spesialisasi'   => 'Umum',
            'jadwal_praktek' => 'Senin - Minggu (08:00 - 20:00)',
            'status'         => 'Tersedia',
        ]);

        // 2. Create a doctor who is 'Libur' (on leave)
        $onLeaveDoctor = Doctor::create([
            'nama'           => 'Dr. Dokter Libur',
            'spesialisasi'   => 'Gigi',
            'jadwal_praktek' => 'Senin - Minggu (08:00 - 20:00)',
            'status'         => 'Libur',
        ]);

        // 3. Create a doctor who is 'Istirahat' (resting)
        $restingDoctor = Doctor::create([
            'nama'           => 'Dr. Dokter Istirahat',
            'spesialisasi'   => 'Anak',
            'jadwal_praktek' => 'Senin - Minggu (08:00 - 20:00)',
            'status'         => 'Istirahat',
        ]);

        // Access the portal as patient
        $response = $this->actingAs($this->pasien)->get(route('portal'));

        $response->assertStatus(200);

        // Assert that the available doctor is shown
        $response->assertSee($availableDoctor->nama);

        // Assert that the libur and istirahat doctors are NOT shown
        $response->assertDontSee($onLeaveDoctor->nama);
        $response->assertDontSee($restingDoctor->nama);
    }

    /** @test */
    public function portal_reservasi_wizard_contains_wizard_attributes_and_elements()
    {
        $response = $this->actingAs($this->pasien)->get(route('portal'));
        $response->assertStatus(200);

        // Check for step indicator
        $response->assertSee('Langkah 1: Detail Reservasi', false);
        $response->assertSee('Langkah 2: Pilih Dokter', false);

        // Check for Lanjut and Kembali buttons
        $response->assertSee('nextStep()', false);
        $response->assertSee('Lanjut Pilih Dokter', false);
        $response->assertSee('currentStep = 1', false);
        $response->assertSee('Kembali', false);

        // Check that crucial fields are marked as required
        $response->assertSee('required', false);
    }
}
