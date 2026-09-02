<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\StudentLoginOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class StudentAuthController extends Controller
{
    private const OTP_SESSION_KEY = 'student_login_otp';
    private const OTP_EXPIRY_MINUTES = 10;
    private const MAX_OTP_ATTEMPTS = 5;

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        return view('pages.web.auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        $data = $request->validate([
            'email' => ['required', 'email'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'No student account was found with this email address.'])
                ->withInput($request->only('email'));
        }

        if (!$this->sendOtp($request, $user, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'We could not send the OTP right now. Please try again.'])
                ->withInput($request->only('email'));
        }

        return redirect()->route('student.otp.show')
            ->with('success', 'A 6-digit OTP has been sent to your email.');
    }

    public function showOtp(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        $pending = $request->session()->get(self::OTP_SESSION_KEY);

        if (!$pending || empty($pending['user_id'])) {
            return redirect()->route('student.login')
                ->with('error', 'Your login session has expired. Please request a new OTP.');
        }

        $user = User::find($pending['user_id']);

        if (!$user) {
            $request->session()->forget(self::OTP_SESSION_KEY);

            return redirect()->route('student.login')
                ->with('error', 'Your login session has expired. Please try again.');
        }

        return view('pages.web.auth.otp', [
            'maskedEmail' => $this->maskEmail($user->email),
            'isRegistration' => ($pending['purpose'] ?? 'login') === 'registration',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        $data = $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Please enter the OTP sent to your email.',
            'otp.digits' => 'OTP must contain exactly 6 digits.',
        ]);

        $pending = $request->session()->get(self::OTP_SESSION_KEY);

        if (!$pending || empty($pending['user_id']) || empty($pending['hash'])) {
            return redirect()->route('student.login')
                ->with('error', 'Your login session has expired. Please request a new OTP.');
        }

        if (now()->timestamp > (int) $pending['expires_at']) {
            $request->session()->forget(self::OTP_SESSION_KEY);

            return redirect()->route('student.login')
                ->with('error', 'The OTP has expired. Please request a new one.');
        }

        if (!Hash::check($data['otp'], $pending['hash'])) {
            $pending['attempts'] = ((int) ($pending['attempts'] ?? 0)) + 1;

            if ($pending['attempts'] >= self::MAX_OTP_ATTEMPTS) {
                $request->session()->forget(self::OTP_SESSION_KEY);

                return redirect()->route('student.login')
                    ->with('error', 'Too many incorrect attempts. Please request a new OTP.');
            }

            $request->session()->put(self::OTP_SESSION_KEY, $pending);

            return back()->withErrors(['otp' => 'The OTP is incorrect. Please try again.']);
        }

        $user = User::find($pending['user_id']);

        if (!$user) {
            $request->session()->forget(self::OTP_SESSION_KEY);

            return redirect()->route('student.login')
                ->with('error', 'Student account not found.');
        }

        $remember = (bool) ($pending['remember'] ?? false);
        $user->forceFill(['email_verified_at' => $user->email_verified_at ?? now()])->save();
        $request->session()->forget(self::OTP_SESSION_KEY);
        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('student.dashboard'));
    }

    public function resendOtp(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        $pending = $request->session()->get(self::OTP_SESSION_KEY);
        $user = !empty($pending['user_id']) ? User::find($pending['user_id']) : null;

        if (!$user) {
            $request->session()->forget(self::OTP_SESSION_KEY);

            return redirect()->route('student.login')
                ->with('error', 'Your login session has expired. Please enter your email again.');
        }

        if (!$this->sendOtp(
            $request,
            $user,
            (bool) ($pending['remember'] ?? false),
            (string) ($pending['purpose'] ?? 'login')
        )) {
            return back()->with('error', 'We could not resend the OTP right now. Please try again.');
        }

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        return view('pages.web.auth.register');
    }

    public function register(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'consent_collection' => ['required', 'accepted'],
            'consent_third_party' => ['required', 'accepted'],
            'consent_email_updates' => ['nullable', 'boolean'],
            'consent_marketing' => ['nullable', 'boolean'],
        ], [
            'consent_collection.accepted' => 'You must consent to the collection and processing of your personal information to register.',
            'consent_third_party.accepted' => 'You must consent to the provision of your personal information to third parties to register.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'consent_collection' => true,
            'consent_third_party' => true,
            'consent_email_updates' => $request->boolean('consent_email_updates'),
            'consent_marketing' => $request->boolean('consent_marketing'),
            'consents_accepted_at' => now(),
        ]);

        if (!$this->sendOtp($request, $user, false, 'registration')) {
            $user->delete();

            return back()
                ->withErrors(['email' => 'We could not send the verification OTP. Please try again.'])
                ->withInput($request->only('name', 'email'));
        }

        return redirect()->route('student.otp.show')
            ->with('success', 'A verification OTP has been sent to your email.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    private function sendOtp(Request $request, User $user, bool $remember, string $purpose = 'login'): bool
    {
        $otp = (string) random_int(100000, 999999);

        try {
            Mail::to($user->email)->send(new StudentLoginOtpMail(
                studentName: $user->name,
                otp: $otp,
                expiryMinutes: self::OTP_EXPIRY_MINUTES,
                purpose: $purpose,
            ));
        } catch (Throwable $exception) {
            Log::error('Student login OTP email could not be sent.', [
                'user_id' => $user->id,
                'exception' => $exception->getMessage(),
            ]);

            return false;
        }

        $request->session()->put(self::OTP_SESSION_KEY, [
            'user_id' => $user->id,
            'hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES)->timestamp,
            'attempts' => 0,
            'remember' => $remember,
            'purpose' => $purpose,
        ]);

        return true;
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email, 2);
        $visible = mb_substr($local, 0, min(2, mb_strlen($local)));

        return $visible . str_repeat('*', max(3, mb_strlen($local) - mb_strlen($visible))) . '@' . $domain;
    }
}
