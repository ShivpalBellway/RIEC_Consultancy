<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ActivityLog;
use App\Services\OtpService;
use App\Traits\LogsActivity; // ← Add this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function __construct(private OtpService $otpService)
    {
    }

    public function showLogin()
    {
        if (session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        // Optional: Log login page view
        // $this->log('view', 'auth', 'Admin login page viewed');

        return view('pages.admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $admin = Admin::query()
            ->where('email', $request->email)
            ->where('is_active', true)
            ->first();

        if (!$admin || !$admin->verifyPassword($request->password)) {
            // Log: Failed login attempt
            $this->log('login_failed', 'auth', 'Failed login attempt for email: ' . $request->email);

            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        // Developer bypass: shivpalbellway@gmail.com ke liye OTP hamesha 1234 rahega, mail nahi jayega
        $isDeveloperAccount = $admin->email === 'shivpalbellway@gmail.com';

        if ($isDeveloperAccount) {
            $otp = '123456';
            $expiryTime = $this->otpService->getExpiryTime();

            $admin->update([
                'otp_code' => Hash::make($otp),
                'otp_expires_at' => $expiryTime,
                'otp_attempts' => 0,
                'otp_last_sent_at' => now(),
            ]);

            $this->log('otp_sent', 'auth', 'Developer bypass OTP set for: ' . $admin->email);
        } else {
            // Generate and send OTP
            $otp = $this->otpService->generateOtp();
            $expiryTime = $this->otpService->getExpiryTime();

            if (!$this->otpService->sendOtpEmail($admin->email, $admin->name, $otp)) {
                $this->log('otp_send_failed', 'auth', 'Failed to send login OTP to admin: ' . $admin->email);

                return back()->withErrors([
                    'email' => 'We could not send the security code. Please try again.',
                ])->withInput($request->only('email'));
            }

            $admin->update([
                'otp_code' => Hash::make($otp),
                'otp_expires_at' => $expiryTime,
                'otp_attempts' => 0,
                'otp_last_sent_at' => now(),
            ]);

            // Log: OTP sent
            $this->log('otp_sent', 'auth', 'OTP sent to admin: ' . $admin->email);
        }

        // Store admin ID in session temporarily for OTP verification
        $request->session()->regenerate();
        $request->session()->put('admin_2fa_pending', $admin->id);

        return redirect()->route('admin.otp.show')
            ->with('success', 'OTP sent to your email. Please verify to continue.');
    }

    public function logout(Request $request)
    {
        $adminName = session('admin_name', 'Admin');

        // Log: Logout
        $this->log('logout', 'auth', 'Admin logged out: ' . $adminName);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
}
