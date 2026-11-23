<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

/**
 * Suite Pengujian White Box: Basis Path Testing (Khusus Fitur Login)
 *
 * Target Method : App\Http\Controllers\LoginController::authenticate()
 * Kompleksitas  : V(G) = 7 (McCabe Cyclomatic Complexity: E - N + 2 = 20 - 15 + 2 = 7)
 * Total Jalur   : 7 Jalur Linier Independen + Analisis Nilai Batas (BVA Validasi Input)
 */
class LoginBasisPathTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('test@delimacare.com|127.0.0.1');
    }

    // =========================================================================
    // IMPLEMENTASI BASIS PATH TESTING (V(G) = 7)
    // =========================================================================

    /**
     * TC-LOGIN-WBT-01: Jalur 1 (Path 1: N1 -> N2 -> N3 -> N15)
     * Menguji kondisi ketika RateLimiter::tooManyAttempts() bernilai True (>= 5 percobaan gagal).
     * Eksekusi langsung berhenti di Node 3 dan mengembalikan pesan error throttle.
     */
    public function test_tc_login_wbt_01_path_1_rate_limit_exceeded_returns_throttle_error(): void
    {
        $email = 'pasien@delimacare.com';
        $throttleKey = $email . '|127.0.0.1';

        // Simulasikan 5 kali percobaan gagal sebelumnya
        for ($i = 0; $i < 5; $i++) {
            RateLimiter::hit($throttleKey, 60);
        }

        $response = $this->from('/login')->post('/login', [
            'email'    => $email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('throttle');
        $this->assertStringContainsString(
            'Terlalu banyak percobaan login',
            session('throttle')
        );
        $this->assertGuest();
    }

    /**
     * TC-LOGIN-WBT-02: Jalur 2 (Path 2: N1 -> N2 -> N4 -> N14 -> N15)
     * Menguji kondisi ketika RateLimiter belum penuh (N2 False), namun Auth::attempt() gagal (N4 False).
     * Eksekusi menuju Node 14 (RateLimiter::hit & return pesan error kredensial salah).
     */
    public function test_tc_login_wbt_02_path_2_invalid_credentials_hits_limiter_and_returns_error(): void
    {
        User::create([
            'username' => 'Pasien Uji',
            'email'    => 'pasien@delimacare.com',
            'password' => bcrypt('password_benar'),
            'role'     => 'pasien',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email'    => 'pasien@delimacare.com',
            'password' => 'password_salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Email atau kata sandi yang Anda masukkan salah.');
        $this->assertEquals(1, RateLimiter::attempts('pasien@delimacare.com|127.0.0.1'));
        $this->assertGuest();
    }

    /**
     * TC-LOGIN-WBT-03: Jalur 3 (Path 3: N1 -> N2 -> N4 -> N5 -> N6 -> N7 -> N15)
     * Menguji kondisi login berhasil (N4 True) dan role user adalah 'admin' (N6 True).
     * Eksekusi keluar di Node 7 dengan redirect ke '/admin/dashboard'.
     */
    public function test_tc_login_wbt_03_path_3_valid_admin_redirects_to_admin_dashboard(): void
    {
        $admin = User::create([
            'username' => 'Admin Klinik',
            'email'    => 'admin@delimacare.com',
            'password' => bcrypt('admin123'),
            'role'     => 'admin',
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@delimacare.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * TC-LOGIN-WBT-04: Jalur 4 (Path 4: N1 -> N2 -> N4 -> N5 -> N6 -> N8 -> N9 -> N15)
     * Menguji kondisi login berhasil (N4 True), bukan admin (N6 False),
     * dan role user adalah 'dokter' (N8 True).
     * Eksekusi keluar di Node 9 dengan redirect ke '/admin/dashboard'.
     */
    public function test_tc_login_wbt_04_path_4_valid_dokter_redirects_to_admin_dashboard(): void
    {
        $dokter = User::create([
            'username' => 'Dr. Budi',
            'email'    => 'dokter@delimacare.com',
            'password' => bcrypt('dokter123'),
            'role'     => 'dokter',
        ]);

        $response = $this->post('/login', [
            'email'    => 'dokter@delimacare.com',
            'password' => 'dokter123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($dokter);
    }

    /**
     * TC-LOGIN-WBT-05: Jalur 5 (Path 5: N1 -> N2 -> N4 -> N5 -> N6 -> N8 -> N10 -> N13 -> N15)
     * Menguji kondisi login berhasil sebagai 'pasien' (N6 False, N8 False),
     * dan tidak ada session 'url.intended' (N10 False).
     * Eksekusi langsung menuju Node 13 dan redirect ke '/portal'.
     */
    public function test_tc_login_wbt_05_path_5_valid_patient_without_intended_url_redirects_to_portal(): void
    {
        $pasien = User::create([
            'username' => 'Siti Pasien',
            'email'    => 'siti@delimacare.com',
            'password' => bcrypt('pasien123'),
            'role'     => 'pasien',
        ]);

        $response = $this->post('/login', [
            'email'    => 'siti@delimacare.com',
            'password' => 'pasien123',
        ]);

        $response->assertRedirect('/portal');
        $this->assertAuthenticatedAs($pasien);
    }

    /**
     * TC-LOGIN-WBT-06: Jalur 6 (Path 6: N1 -> N2 -> N4 -> N5 -> N6 -> N8 -> N10 -> N11 -> N13 -> N15)
     * Menguji kondisi login berhasil sebagai 'pasien' (N6 False, N8 False),
     * memiliki session 'url.intended' (N10 True), tetapi BUKAN mengarah ke '/admin' (N11 False).
     * Session 'url.intended' dipertahankan dan pasien diarahkan ke URL intended tersebut.
     */
    public function test_tc_login_wbt_06_path_6_valid_patient_with_non_admin_intended_url_redirects_to_intended(): void
    {
        $pasien = User::create([
            'username' => 'Siti Pasien',
            'email'    => 'siti@delimacare.com',
            'password' => bcrypt('pasien123'),
            'role'     => 'pasien',
        ]);

        $response = $this->withSession(['url.intended' => 'http://localhost/portal?tab=riwayat'])
            ->post('/login', [
                'email'    => 'siti@delimacare.com',
                'password' => 'pasien123',
            ]);

        $response->assertRedirect('http://localhost/portal?tab=riwayat');
        $this->assertAuthenticatedAs($pasien);
    }

    /**
     * TC-LOGIN-WBT-07: Jalur 7 (Path 7: N1 -> N2 -> N4 -> N5 -> N6 -> N8 -> N10 -> N11 -> N12 -> N13 -> N15)
     * Menguji kondisi login berhasil sebagai 'pasien' (N6 False, N8 False),
     * memiliki session 'url.intended' (N10 True) yang mengandung '/admin' (N11 True).
     * Eksekusi masuk ke Node 12 untuk menghapus 'url.intended' lalu redirect aman ke '/portal' (Node 13).
     */
    public function test_tc_login_wbt_07_path_7_valid_patient_with_admin_intended_url_clears_intended_and_redirects_to_portal(): void
    {
        $pasien = User::create([
            'username' => 'Siti Pasien',
            'email'    => 'siti@delimacare.com',
            'password' => bcrypt('pasien123'),
            'role'     => 'pasien',
        ]);

        $response = $this->withSession(['url.intended' => 'http://localhost/admin/dashboard'])
            ->post('/login', [
                'email'    => 'siti@delimacare.com',
                'password' => 'pasien123',
            ]);

        $response->assertRedirect('/portal');
        $this->assertAuthenticatedAs($pasien);
    }

    // =========================================================================
    // ANALISIS NILAI BATAS (BOUNDARY VALUE ANALYSIS) - VALIDASI INPUT (NODE 1)
    // =========================================================================

    /**
     * BVA-LOGIN-01: Panjang password 5 karakter (di bawah batas minimum 6) -> Harus Ditolak
     */
    public function test_bva_password_five_chars_below_minimum_is_rejected(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email'    => 'siti@delimacare.com',
            'password' => '12345', // 5 karakter (< 6)
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'password' => 'Kata sandi minimal 6 karakter.',
        ]);
    }

    /**
     * BVA-LOGIN-02: Panjang password tepat 6 karakter (tepat di batas minimum) -> Lolos Validasi
     */
    public function test_bva_password_exact_six_chars_passes_validation(): void
    {
        $pasien = User::create([
            'username' => 'Siti Pasien',
            'email'    => 'siti@delimacare.com',
            'password' => bcrypt('123456'), // Tepat 6 karakter
            'role'     => 'pasien',
        ]);

        $response = $this->post('/login', [
            'email'    => 'siti@delimacare.com',
            'password' => '123456',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/portal');
        $this->assertAuthenticatedAs($pasien);
    }
}
