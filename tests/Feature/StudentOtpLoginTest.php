<?php

namespace Tests\Feature;

use App\Mail\StudentLoginOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudentOtpLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_student_can_request_a_login_otp(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $response = $this->post(route('student.login.post'), [
            'email' => $user->email,
            'remember' => true,
        ]);

        $response->assertRedirect(route('student.otp.show'));
        $response->assertSessionHas('student_login_otp', function (array $pending) use ($user) {
            return $pending['user_id'] === $user->id
                && $pending['remember'] === true
                && $pending['attempts'] === 0
                && !empty($pending['hash']);
        });

        Mail::assertSent(StudentLoginOtpMail::class, function (StudentLoginOtpMail $mail) use ($user) {
            return $mail->hasTo($user->email)
                && strlen($mail->otp) === 6;
        });
    }

    public function test_student_registers_without_password_and_is_logged_in_after_email_otp_verification(): void
    {
        Mail::fake();
        $otp = null;

        $response = $this->post(route('student.register.post'), [
            'name' => 'New Student',
            'email' => 'new.student@example.com',
        ]);

        $response->assertRedirect(route('student.otp.show'));
        $this->assertGuest();

        $user = User::where('email', 'new.student@example.com')->firstOrFail();
        $this->assertNull($user->password);
        $this->assertNull($user->email_verified_at);

        Mail::assertSent(StudentLoginOtpMail::class, function (StudentLoginOtpMail $mail) use (&$otp) {
            $otp = $mail->otp;

            return $mail->purpose === 'registration';
        });

        $verifyResponse = $this->post(route('student.otp.verify'), ['otp' => $otp]);

        $verifyResponse->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_student_can_login_with_the_emailed_otp(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $otp = null;

        $this->post(route('student.login.post'), ['email' => $user->email]);

        Mail::assertSent(StudentLoginOtpMail::class, function (StudentLoginOtpMail $mail) use (&$otp) {
            $otp = $mail->otp;

            return true;
        });

        $response = $this->post(route('student.otp.verify'), ['otp' => $otp]);

        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionMissing('student_login_otp');
        $this->assertAuthenticatedAs($user);
    }

    public function test_incorrect_otp_is_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->withSession([
                'student_login_otp' => [
                    'user_id' => $user->id,
                    'hash' => Hash::make('123456'),
                    'expires_at' => now()->addMinutes(10)->timestamp,
                    'attempts' => 0,
                    'remember' => false,
                ],
            ])
            ->from(route('student.otp.show'))
            ->post(route('student.otp.verify'), ['otp' => '654321']);

        $response->assertRedirect(route('student.otp.show'));
        $response->assertSessionHasErrors('otp');
        $response->assertSessionHas('student_login_otp.attempts', 1);
        $this->assertGuest();
    }

    public function test_expired_otp_returns_student_to_login(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->withSession([
                'student_login_otp' => [
                    'user_id' => $user->id,
                    'hash' => Hash::make('123456'),
                    'expires_at' => now()->subMinute()->timestamp,
                    'attempts' => 0,
                    'remember' => false,
                ],
            ])
            ->post(route('student.otp.verify'), ['otp' => '123456']);

        $response->assertRedirect(route('student.login'));
        $response->assertSessionMissing('student_login_otp');
        $this->assertGuest();
    }

    public function test_unknown_email_does_not_create_an_otp_session(): void
    {
        Mail::fake();

        $response = $this->from(route('student.login'))->post(route('student.login.post'), [
            'email' => 'missing@example.com',
        ]);

        $response->assertRedirect(route('student.login'));
        $response->assertSessionHasErrors('email');
        $response->assertSessionMissing('student_login_otp');
        Mail::assertNothingSent();
    }
}
