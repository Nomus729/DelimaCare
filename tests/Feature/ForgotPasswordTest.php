<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'username' => 'test_user',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'pasien',
        ]);
    }

    /** @test */
    public function test_forgot_password_form_can_be_rendered()
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    /** @test */
    public function test_sending_reset_link_requires_email()
    {
        $response = $this->post(route('password.email'), [
            'email' => '',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function test_sending_reset_link_fails_for_non_existent_email()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function test_sending_reset_link_sends_email_and_saves_otp()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'test@example.com',
        ]);

        // Asserts redirect to reset page
        $response->assertRedirect(route('password.reset', ['email' => 'test@example.com']));
        $response->assertSessionHas('success');

        // Check DB has the token record
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', 'test@example.com')
            ->first();

        $this->assertNotNull($tokenRecord);
        $this->assertNotNull($tokenRecord->token);
        // Assert token is a 6-digit numeric OTP
        $this->assertTrue(is_numeric($tokenRecord->token));
        $this->assertEquals(6, strlen($tokenRecord->token));

        // Check Mail was sent using array driver
        $emails = app('mailer')->getSymfonyTransport()->messages();
        $this->assertCount(1, $emails);
        
        $email = $emails[0]->getOriginalMessage();
        $this->assertEquals('test@example.com', $email->getTo()[0]->getAddress());
        $this->assertEquals('Kode OTP Reset Password DelimaCare', $email->getSubject());
    }

    /** @test */
    public function test_reset_password_form_can_be_rendered()
    {
        $response = $this->get(route('password.reset', ['email' => 'test@example.com']));
        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
        $response->assertViewHas('email', 'test@example.com');
    }

    /** @test */
    public function test_reset_password_fails_with_invalid_otp()
    {
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => '123456',
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.update'), [
            'email' => 'test@example.com',
            'code' => '654321', // wrong code
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('error');
    }

    /** @test */
    public function test_reset_password_succeeds_with_valid_otp()
    {
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => '123456',
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.update'), [
            'email' => 'test@example.com',
            'code' => '123456',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');

        // Check token is deleted from DB
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);

        // Attempt login with new password to verify
        $this->assertTrue(auth()->attempt([
            'email' => 'test@example.com',
            'password' => 'newpassword123',
        ]));
    }
}
