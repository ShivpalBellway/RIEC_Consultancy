<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login OTP</title>
</head>
<body style="margin:0;background:#f5f9ff;font-family:Arial,sans-serif;color:#17365d;">
    <div style="max-width:560px;margin:32px auto;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;">
        <div style="background:#17365d;color:#ffffff;padding:24px;text-align:center;">
            <h1 style="margin:0;font-size:24px;">{{ $purpose === 'registration' ? 'Verify Your Email' : 'REIAC Student Login' }}</h1>
        </div>
        <div style="padding:32px;">
            <p style="margin-top:0;">Hello {{ $studentName }},</p>
            <p>
                {{ $purpose === 'registration'
                    ? 'Use this one-time password to verify your email and complete your student registration:'
                    : 'Use this one-time password to sign in to your student account:' }}
            </p>
            <div style="margin:28px 0;padding:18px;text-align:center;background:#f5f9ff;border-radius:12px;font-size:32px;font-weight:700;letter-spacing:8px;color:#17365d;">
                {{ $otp }}
            </div>
            <p>This OTP expires in {{ $expiryMinutes }} minutes and can be used only once.</p>
            <p style="margin-bottom:0;color:#6b7280;font-size:13px;">If you did not request this login, you can safely ignore this email. Never share this OTP with anyone.</p>
        </div>
    </div>
</body>
</html>
