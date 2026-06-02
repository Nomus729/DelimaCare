<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ConsultationMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ConsultationTimeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $patient;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin
        $this->admin = User::create([
            'username' => 'admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Create patient
        $this->patient = User::create([
            'username' => 'john_doe',
            'email'    => 'john@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);
    }

    /** @test */
    public function patient_chat_messages_are_formatted_in_jakarta_timezone()
    {
        // 1. Freeze time to a specific local time in Asia/Jakarta
        $localTime = Carbon::create(2026, 6, 3, 14, 35, 0, 'Asia/Jakarta');
        Carbon::setTestNow($localTime);

        // 2. Create message
        ConsultationMessage::create([
            'username' => 'john_doe',
            'sender'   => 'user',
            'type'     => 'text',
            'message'  => 'Halo dok',
        ]);

        // 3. Request messages from patient portal
        $response = $this->actingAs($this->patient)
            ->get(route('chat.load'));

        $response->assertStatus(200);
        
        $messages = $response->json('messages');
        $this->assertCount(1, $messages);
        
        // The formatted time should be "14:35" (in Asia/Jakarta), not "07:35" (in UTC)
        $this->assertEquals('14:35', $messages[0]['time']);

        Carbon::setTestNow(); // Reset time freeze
    }

    /** @test */
    public function admin_chat_endpoints_return_correct_local_time()
    {
        // Freeze time to 09:12 AM Jakarta time
        $localTime = Carbon::create(2026, 6, 3, 9, 12, 0, 'Asia/Jakarta');
        Carbon::setTestNow($localTime);

        ConsultationMessage::create([
            'username' => 'john_doe',
            'sender'   => 'user',
            'type'     => 'text',
            'message'  => 'Ada dokter?',
        ]);

        // 1. Test getAdminMessages
        $response1 = $this->actingAs($this->admin)
            ->get('/admin/chat/john_doe');

        $response1->assertStatus(200);
        $messages = $response1->json('messages');
        $this->assertCount(1, $messages);
        $this->assertEquals('09:12', $messages[0]['time']);

        // 2. Test getChatUsers list
        $response2 = $this->actingAs($this->admin)
            ->get('/admin/chat/users');

        $response2->assertStatus(200);
        $users = $response2->json('users');
        $this->assertCount(1, $users);
        $this->assertEquals('09:12', $users[0]['time']);

        Carbon::setTestNow();
    }

    /** @test */
    public function patient_and_admin_sending_messages_saves_in_local_timezone()
    {
        $localTime = Carbon::create(2026, 6, 3, 20, 45, 0, 'Asia/Jakarta');
        Carbon::setTestNow($localTime);

        // 1. Patient sends message
        $responsePatient = $this->actingAs($this->patient)
            ->post(route('chat.send'), [
                'message' => 'Pesan pasien baru',
            ]);

        $responsePatient->assertStatus(200);
        
        // Assert stored timestamp matches 20:45
        $msg1 = ConsultationMessage::first();
        $this->assertEquals('20:45', $msg1->created_at->format('H:i'));

        // 2. Admin sends message
        $responseAdmin = $this->actingAs($this->admin)
            ->post('/admin/chat/send', [
                'user_id' => 'john_doe',
                'message' => 'Halo dari admin',
            ]);

        $responseAdmin->assertStatus(200);

        $msg2 = ConsultationMessage::orderBy('id', 'desc')->first();
        $this->assertEquals('20:45', $msg2->created_at->format('H:i'));

        Carbon::setTestNow();
    }
}
