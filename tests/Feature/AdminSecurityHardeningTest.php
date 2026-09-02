<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Admin;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    private function admin(array $attributes = []): Admin
    {
        return Admin::create(array_merge([
            'name' => 'Secure Admin',
            'email' => 'admin@example.com',
            'password' => 'secure-password',
            'is_active' => true,
        ], $attributes));
    }

    public function test_inactive_admin_cannot_login_and_failed_attempt_is_logged(): void
    {
        $this->admin(['is_active' => false]);

        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@example.com',
            'password' => 'secure-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('activity_logs', ['action' => 'login_failed']);
        $this->assertFalse($response->getSession()->has('admin_id'));
    }

    public function test_password_login_starts_mandatory_hashed_otp_challenge(): void
    {
        $admin = $this->admin();
        $service = $this->mock(OtpService::class);
        $service->shouldReceive('generateOtp')->once()->andReturn('123456');
        $service->shouldReceive('getExpiryTime')->once()->andReturn(now()->addMinutes(5));
        $service->shouldReceive('sendOtpEmail')
            ->once()
            ->with($admin->email, $admin->name, '123456')
            ->andReturnTrue();

        $response = $this->post(route('admin.login.post'), [
            'email' => $admin->email,
            'password' => 'secure-password',
        ]);

        $response->assertRedirect(route('admin.otp.show'));
        $response->assertSessionHas('admin_2fa_pending', $admin->id);
        $this->assertFalse($response->getSession()->has('admin_id'));
        $this->assertTrue(Hash::check('123456', $admin->fresh()->otp_code));
        $this->assertNotSame('123456', $admin->fresh()->otp_code);
        $this->assertDatabaseHas('activity_logs', ['action' => 'otp_sent']);
    }

    public function test_successful_otp_creates_admin_session_and_logs_login(): void
    {
        $admin = $this->admin([
            'otp_code' => Hash::make('123456'),
            'otp_expires_at' => now()->addMinutes(5),
            'otp_attempts' => 0,
            'otp_last_sent_at' => now(),
        ]);

        $response = $this->withSession(['admin_2fa_pending' => $admin->id])
            ->post(route('admin.otp.verify'), ['otp' => '123456']);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('admin_id', $admin->id);
        $this->assertFalse($response->getSession()->has('admin_2fa_pending'));
        $this->assertNull($admin->fresh()->otp_code);
        $this->assertDatabaseHas('activity_logs', ['action' => 'otp_verified']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'login_success']);
    }

    public function test_otp_is_locked_after_five_incorrect_attempts(): void
    {
        $admin = $this->admin([
            'otp_code' => Hash::make('123456'),
            'otp_expires_at' => now()->addMinutes(5),
            'otp_attempts' => 0,
            'otp_last_sent_at' => now(),
        ]);

        $this->withSession(['admin_2fa_pending' => $admin->id]);

        for ($attempt = 1; $attempt <= OtpService::MAX_OTP_ATTEMPTS; $attempt++) {
            $response = $this->post(route('admin.otp.verify'), ['otp' => '000000']);
        }

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('error', 'Too many incorrect OTP attempts. Please login again to request a new code.');
        $this->assertSame(OtpService::MAX_OTP_ATTEMPTS, $admin->fresh()->otp_attempts);
        $this->assertNull($admin->fresh()->otp_code);
        $this->assertFalse($response->getSession()->has('admin_2fa_pending'));
        $this->assertDatabaseHas('activity_logs', ['action' => 'otp_locked']);
    }

    public function test_otp_resend_has_a_sixty_second_cooldown(): void
    {
        $admin = $this->admin([
            'otp_code' => Hash::make('123456'),
            'otp_expires_at' => now()->addMinutes(5),
            'otp_last_sent_at' => now(),
        ]);
        $service = $this->mock(OtpService::class);
        $service->shouldNotReceive('generateOtp');
        $service->shouldNotReceive('sendOtpEmail');

        $response = $this->withSession(['admin_2fa_pending' => $admin->id])
            ->from(route('admin.otp.show'))
            ->post(route('admin.otp.resend'));

        $response->assertRedirect(route('admin.otp.show'));
        $this->assertStringContainsString(
            'Please wait',
            (string) $response->getSession()->get('error')
        );
        $this->assertDatabaseHas('activity_logs', ['action' => 'otp_resend_blocked']);
    }

    public function test_inactive_admin_is_blocked_again_at_otp_stage(): void
    {
        $admin = $this->admin([
            'is_active' => false,
            'otp_code' => Hash::make('123456'),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        $response = $this->withSession(['admin_2fa_pending' => $admin->id])
            ->post(route('admin.otp.verify'), ['otp' => '123456']);

        $response->assertRedirect(route('admin.login'));
        $this->assertFalse($response->getSession()->has('admin_id'));
        $this->assertFalse($response->getSession()->has('admin_2fa_pending'));
    }

    public function test_logout_invalidates_session_and_is_logged(): void
    {
        $admin = $this->admin();

        $response = $this->withSession([
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'admin_email' => $admin->email,
        ])->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertFalse($response->getSession()->has('admin_id'));
        $this->assertDatabaseHas('activity_logs', ['action' => 'logout']);
    }

    public function test_inactive_admin_session_cannot_access_protected_pages(): void
    {
        $admin = $this->admin(['is_active' => false]);

        $response = $this->withSession([
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
        ])->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas(
            'error',
            'Your admin account is inactive. Please contact the system administrator.'
        );
        $this->assertFalse($response->getSession()->has('admin_id'));
    }
}
