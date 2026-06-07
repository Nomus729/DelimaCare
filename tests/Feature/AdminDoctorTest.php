<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDoctorTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $patient;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::create([
            'username' => 'admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Create a patient user
        $this->patient = User::create([
            'username' => 'pasien',
            'email'    => 'pasien@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);
    }

    /** @test */
    public function admin_can_access_doctors_partial_endpoint()
    {
        // Pre-populate a doctor
        Doctor::create([
            'nama' => 'Dr. Budi Santoso',
            'spesialisasi' => 'Dokter Umum',
            'status' => 'Tersedia',
            'jadwal_praktek' => 'Senin - Jumat (08:00 - 16:00)',
            'phone' => '08123456789',
        ]);

        $response = $this->actingAs($this->admin)
            ->withHeaders(['HX-Request' => 'true'])
            ->get(route('admin.doctors.partial'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.partials.doctors');
        $response->assertViewHas('doctors');
        $response->assertSee('Dr. Budi Santoso');
        $response->assertSee('Dokter Umum');
    }

    /** @test */
    public function non_admin_cannot_access_doctors_partial_endpoint()
    {
        $response = $this->actingAs($this->patient)
            ->withHeaders(['HX-Request' => 'true'])
            ->get(route('admin.doctors.partial'));

        $response->assertStatus(302);
    }

    /** @test */
    public function admin_can_store_a_new_doctor()
    {
        $doctorData = [
            'nama' => 'Dr. Siti Aminah, Sp.OG',
            'spesialisasi' => 'Kebidanan & Kandungan',
            'status' => 'Tersedia',
            'jadwal_praktek' => 'Senin - Rabu (09:00 - 14:00)',
            'phone' => '08987654321',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.doctors.store'), $doctorData);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Data dokter berhasil ditambahkan!');

        $this->assertDatabaseHas('doctors', [
            'nama' => 'Dr. Siti Aminah, Sp.OG',
            'spesialisasi' => 'Kebidanan & Kandungan',
            'status' => 'Tersedia',
        ]);
    }

    /** @test */
    public function admin_can_store_a_new_doctor_with_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('doctor_siti.jpg', 100);

        $doctorData = [
            'nama' => 'Dr. Siti Aminah, Sp.OG',
            'spesialisasi' => 'Kebidanan & Kandungan',
            'status' => 'Tersedia',
            'jadwal_praktek' => 'Senin - Rabu (09:00 - 14:00)',
            'phone' => '08987654321',
            'image' => $file,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.doctors.store'), $doctorData);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Data dokter berhasil ditambahkan!');

        $doctor = Doctor::where('nama', 'Dr. Siti Aminah, Sp.OG')->first();
        $this->assertNotNull($doctor->image);
        Storage::disk('public')->assertExists($doctor->image);
    }

    /** @test */
    public function admin_can_update_an_existing_doctor()
    {
        $doctor = Doctor::create([
            'nama' => 'Dr. Hermawan',
            'spesialisasi' => 'Bidan',
            'status' => 'Istirahat',
            'jadwal_praktek' => 'Kamis - Jumat (10:00 - 15:00)',
            'phone' => '082233445566',
        ]);

        $updatedData = [
            'nama' => 'Dr. Hermawan Agung',
            'spesialisasi' => 'Spesialis Anak',
            'status' => 'Libur',
            'jadwal_praktek' => 'Kamis - Sabtu (08:00 - 12:00)',
            'phone' => '082233445577',
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.doctors.update', $doctor->id), $updatedData);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Data dokter berhasil diperbarui!');

        $this->assertDatabaseHas('doctors', [
            'id' => $doctor->id,
            'nama' => 'Dr. Hermawan Agung',
            'spesialisasi' => 'Spesialis Anak',
            'status' => 'Libur',
        ]);
    }

    /** @test */
    public function admin_can_update_an_existing_doctor_with_image()
    {
        Storage::fake('public');

        $doctor = Doctor::create([
            'nama' => 'Dr. Hermawan',
            'spesialisasi' => 'Bidan',
            'status' => 'Istirahat',
            'jadwal_praktek' => 'Kamis - Jumat (10:00 - 15:00)',
            'phone' => '082233445566',
        ]);

        $file = UploadedFile::fake()->create('doctor_new.png', 100);

        $updatedData = [
            'nama' => 'Dr. Hermawan Agung',
            'spesialisasi' => 'Spesialis Anak',
            'status' => 'Libur',
            'jadwal_praktek' => 'Kamis - Sabtu (08:00 - 12:00)',
            'phone' => '082233445577',
            'image' => $file,
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.doctors.update', $doctor->id), $updatedData);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Data dokter berhasil diperbarui!');

        $doctor->refresh();
        $this->assertNotNull($doctor->image);
        Storage::disk('public')->assertExists($doctor->image);
    }

    /** @test */
    public function admin_can_delete_a_doctor()
    {
        $doctor = Doctor::create([
            'nama' => 'Dr. Joni',
            'spesialisasi' => 'Dokter Umum',
            'status' => 'Tersedia',
            'jadwal_praktek' => 'Senin - Jumat (08:00 - 16:00)',
            'phone' => '0877889900',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.doctors.destroy', $doctor->id));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Data dokter berhasil dihapus!');

        $this->assertDatabaseMissing('doctors', [
            'id' => $doctor->id,
        ]);
    }

    /** @test */
    public function deleting_doctor_removes_their_image_from_storage()
    {
        Storage::fake('public');

        // Create a doctor with a fake image stored
        $file = UploadedFile::fake()->create('doctor_to_delete.jpg', 100);
        $path = $file->store('doctors', 'public');

        $doctor = Doctor::create([
            'nama' => 'Dr. Joni',
            'spesialisasi' => 'Dokter Umum',
            'status' => 'Tersedia',
            'jadwal_praktek' => 'Senin - Jumat (08:00 - 16:00)',
            'phone' => '0877889900',
            'image' => $path,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.doctors.destroy', $doctor->id));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('doctors', ['id' => $doctor->id]);

        Storage::disk('public')->assertMissing($path);
    }
}
