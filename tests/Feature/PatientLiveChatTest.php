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
