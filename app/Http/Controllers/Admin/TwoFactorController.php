<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\OtpService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TwoFactorController extends Controller
{
    use LogsActivity;

    public function __construct(private OtpService $otpService)
    {
    }

    public function showOtpVerification(Request $request)
    {
        if (!$this->pendingActiveAdmin($request)) {
            return redirect()->route('admin.login')
                ->with('error', 'Session expired or the admin account is inactive. Please login again.');
        }

        $this->log('view_otp_form', 'auth', 'Admin viewed OTP verification page');

        return view('pages.admin.auth.otp-verification');
    }

    public function verifyOtp(Request $request)
    {
        $admin = $this->pendingActiveAdmin($request);

        if (!$admin) {
            return redirect()->route('admin.login')
                ->with('error', 'Session expired or the admin account is inactive. Please login again.');
        }

        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Please enter the OTP.',
            'otp.digits' => 'OTP must be 6 digits.',
        ]);

        if ($admin->otp_attempts >= OtpService::MAX_OTP_ATTEMPTS) {
            $this->expireChallenge($request, $admin);
            $this->log('otp_locked', 'auth', 'OTP verification locked for admin: ' . $admin->email);

            return redirect()->route('admin.login')
                ->with('error', 'Too many incorrect OTP attempts. Please login again to request a new code.');
        }

        if (!$this->otpService->verifyOtp(
            $admin->otp_code,
            $admin->otp_expires_at,
            $request->otp
        )) {
            $attempts = $admin->otp_attempts + 1;
            $admin->update(['otp_attempts' => $attempts]);

            if ($attempts >= OtpService::MAX_OTP_ATTEMPTS) {
                $this->expireChallenge($request, $admin);
                $this->log('otp_locked', 'auth', 'OTP verification locked after 5 failed attempts for admin: ' . $admin->email);

                return redirect()->route('admin.login')
                    ->with('error', 'Too many incorrect OTP attempts. Please login again to request a new code.');
            }

            $remaining = OtpService::MAX_OTP_ATTEMPTS - $attempts;
            $this->log('otp_failed', 'auth', 'OTP verification failed for admin: ' . $admin->email . '. Attempts remaining: ' . $remaining);

            return back()
                ->withErrors(['otp' => 'Invalid or expired OTP. ' . $remaining . ' attempt(s) remaining.'])
                ->withInput();
        }

        $admin->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'otp_last_sent_at' => null,
        ]);

        $this->log('otp_verified', 'auth', 'OTP verified successfully for admin: ' . $admin->email);

        $request->session()->forget('admin_2fa_pending');
        $request->session()->regenerate();
        $request->session()->put([
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'admin_email' => $admin->email,
        ]);

        $this->log('login_success', 'auth', 'Admin logged in successfully: ' . $admin->name);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Welcome back, ' . $admin->name . '! Login successful.');
    }

    public function resendOtp(Request $request)
    {
        $admin = $this->pendingActiveAdmin($request);

        if (!$admin) {
            return redirect()->route('admin.login')
                ->with('error', 'Session expired or the admin account is inactive. Please login again.');
        }

        $availableAt = $admin->otp_last_sent_at?->copy()
            ->addSeconds(OtpService::RESEND_COOLDOWN_SECONDS);

        if ($availableAt && $availableAt->isFuture()) {
            $seconds = (int) ceil(now()->diffInSeconds($availableAt));
            $this->log('otp_resend_blocked', 'auth', 'OTP resend blocked by cooldown for admin: ' . $admin->email);

            return back()->with(
                'error',
                'Please wait ' . $seconds . ' second(s) before requesting another OTP.'
            );
        }

        $otp = $this->otpService->generateOtp();

        if (!$this->otpService->sendOtpEmail($admin->email, $admin->name, $otp)) {
            $this->log('otp_resend_failed', 'auth', 'OTP resend failed for admin: ' . $admin->email);

            return back()->with('error', 'Failed to send OTP. Please try again.');
        }

        $admin->update([
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => $this->otpService->getExpiryTime(),
            'otp_attempts' => 0,
            'otp_last_sent_at' => now(),
        ]);

        $this->log('otp_resend', 'auth', 'OTP resent to admin: ' . $admin->email);

        return back()->with('success', 'OTP has been resent to your email.');
    }

    private function pendingActiveAdmin(Request $request): ?Admin
    {
        $adminId = $request->session()->get('admin_2fa_pending');

        if (!$adminId) {
            return null;
        }

        $admin = Admin::query()
            ->whereKey($adminId)
            ->where('is_active', true)
            ->first();

        if (!$admin) {
            $request->session()->forget('admin_2fa_pending');
        }

        return $admin;
    }

    private function expireChallenge(Request $request, Admin $admin): void
    {
        $admin->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);
        $request->session()->forget('admin_2fa_pending');
    }
}
