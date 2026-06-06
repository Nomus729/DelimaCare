<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PatientProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $pasien;

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
    }

    /** @test */
    public function patient_profile_modal_contains_preview_attributes()
    {
        $response = $this->actingAs($this->pasien)->get(route('portal'));

        $response->assertStatus(200);
        $response->assertSee('id="profilAvatarPreview"', false);
        $response->assertSee('id="profilAvatarInitial"', false);
        $response->assertSee('onchange="previewFotoProfil(this)"', false);
        $response->assertSee('id="clientFotoError"', false);
        $response->assertSee('id="btnSubmitProfil"', false);
    }

    /** @test */
    public function patient_can_update_profile_username_and_email()
    {
        $response = $this->actingAs($this->pasien)
            ->put(route('portal.profil.update'), [
                'username' => 'new_username',
                'email' => 'new_email@delimacare.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->pasien->id,
            'username' => 'new_username',
            'email' => 'new_email@delimacare.com',
        ]);
    }

    /** @test */
    public function patient_can_upload_profile_picture()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->pasien)
            ->put(route('portal.profil.update'), [
                'username' => $this->pasien->username,
                'email' => $this->pasien->email,
                'foto' => $file,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Refresh model
        $this->pasien->refresh();

        $this->assertNotNull($this->pasien->foto);
        Storage::disk('public')->assertExists($this->pasien->foto);
    }

    /** @test */
    public function uploading_new_profile_picture_deletes_old_one()
    {
        Storage::fake('public');

        // Upload first photo
        $oldFile = UploadedFile::fake()->create('old_avatar.jpg', 100, 'image/jpeg');
        $oldPath = $oldFile->store('profil_pasien', 'public');
        $this->pasien->foto = $oldPath;
        $this->pasien->save();

        Storage::disk('public')->assertExists($oldPath);

        // Refresh model from database to make sure it's current
        $this->pasien->refresh();

        // Upload new photo
        $newFile = UploadedFile::fake()->create('new_avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->pasien)
            ->put(route('portal.profil.update'), [
                'username' => $this->pasien->username,
                'email' => $this->pasien->email,
                'foto' => $newFile,
            ]);

        $response->assertRedirect();
        $this->pasien->refresh();

        // Old file should be deleted, new file should exist
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($this->pasien->foto);
    }

    /** @test */
    public function profile_update_validates_email_and_username_uniqueness()
    {
        // Buat user lain
        User::create([
            'username' => 'other_user',
            'email' => 'other@delimacare.com',
            'password' => bcrypt('password'),
            'role' => 'pasien',
        ]);

        // Coba ganti email/username ke milik user lain
        $response = $this->actingAs($this->pasien)
            ->from(route('portal'))
            ->put(route('portal.profil.update'), [
                'username' => 'other_user',
                'email' => 'other@delimacare.com',
            ]);

        $response->assertRedirect(route('portal'));
        $response->assertSessionHasErrors(['username', 'email']);
    }

    /** @test */
    public function profile_update_validates_uploaded_file_type_and_size()
    {
        Storage::fake('public');

        // File too big (e.g. 3MB)
        $largeFile = UploadedFile::fake()->create('large_image.jpg', 3000);

        $response = $this->actingAs($this->pasien)
            ->from(route('portal'))
            ->put(route('portal.profil.update'), [
                'username' => $this->pasien->username,
                'email' => $this->pasien->email,
                'foto' => $largeFile,
            ]);

        $response->assertRedirect(route('portal'));
        $response->assertSessionHasErrors(['foto']);

        // Invalid file format
        $txtFile = UploadedFile::fake()->create('document.txt', 100);

        $response = $this->actingAs($this->pasien)
            ->from(route('portal'))
            ->put(route('portal.profil.update'), [
                'username' => $this->pasien->username,
                'email' => $this->pasien->email,
                'foto' => $txtFile,
            ]);

        $response->assertRedirect(route('portal'));
        $response->assertSessionHasErrors(['foto']);
    }

    /** @test */
    public function profile_shows_error_message_for_foto_validation_failures()
    {
        $errors = new \Illuminate\Support\ViewErrorBag;
        $bag = new \Illuminate\Support\MessageBag(['foto' => ['Ukuran foto profil tidak boleh lebih dari 2048 kilobita.']]);
        $errors->put('default', $bag);

        $response = $this->actingAs($this->pasien)
            ->withSession(['errors' => $errors])
            ->get(route('portal'));

        $response->assertStatus(200);
        $response->assertSee('Ukuran foto profil tidak boleh lebih dari 2048 kilobita.');
    }
}
