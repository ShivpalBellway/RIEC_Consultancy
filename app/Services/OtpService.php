<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    const OTP_LENGTH = 6;
    const OTP_EXPIRY_MINUTES = 5;
    const MAX_OTP_ATTEMPTS = 5;
    const RESEND_COOLDOWN_SECONDS = 60;

    /**
     * Generate a random 6-digit OTP
     */
    public function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), self::OTP_LENGTH, '0', STR_PAD_LEFT);
    }

    /**
     * Get OTP expiry time
     */
    public function getExpiryTime(): Carbon
    {
        return Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES);
    }

    /**
     * Verify if OTP is valid and not expired
     */
    public function verifyOtp(?string $storedOtp, mixed $expiryTime, string $providedOtp): bool
    {
        if (!$storedOtp || !$expiryTime || Carbon::parse($expiryTime)->isPast()) {
            return false;
        }

        try {
            return Hash::check($providedOtp, $storedOtp);
        } catch (\RuntimeException) {
            return false;
        }
    }

    /**
     * Send OTP via email
     */
    public function sendOtpEmail(string $email, string $adminName, string $otp): bool
    {
        try {
            Mail::send('emails.otp-verification', [
                'name' => $adminName,
                'otp' => $otp,
                'expiry_minutes' => self::OTP_EXPIRY_MINUTES,
            ], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Your Admin Login OTP - REIAC');
            });

            return true;

        } catch (\Exception $e) {

            Log::error('OTP Email sending failed: ' . $e->getMessage(), [
                'email' => $email,
            ]);

            return false;
        }
    }
}
