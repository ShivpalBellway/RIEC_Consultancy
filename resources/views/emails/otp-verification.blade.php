<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1a2f5e 0%, #081733 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .otp-box {
            background: linear-gradient(135deg, #f5f5f5 0%, #ebebeb 100%);
            border: 2px solid #dca737;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 48px;
            font-weight: bold;
            color: #1a2f5e;
            letter-spacing: 8px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        .expiry {
            color: #d9534f;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }
        .instructions {
            background: #e7f3ff;
            border-left: 4px solid #1a2f5e;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .instructions p {
            margin: 5px 0;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .security-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            font-size: 13px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #1a2f5e 0%, #081733 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 OTP Verification</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Secure Admin Login</p>
        </div>

        <div class="content">
            <p class="greeting">Hi {{ $name }},</p>

            <p>You're receiving this email because someone (hopefully you!) initiated a login to your REIAC admin account.</p>

            <p>To complete your login, please use the following One-Time Password (OTP):</p>

            <div class="otp-box">
                <p style="margin: 0 0 10px 0; color: #666;">Your OTP Code</p>
                <div class="otp-code">{{ $otp }}</div>
                <div class="expiry">⏱️ Expires in {{ $expiry_minutes }} minutes</div>
            </div>

            <div class="instructions">
                <p><strong>📋 Instructions:</strong></p>
                <p>1. Go to the admin login page</p>
                <p>2. After entering your password, you'll be prompted for the OTP</p>
                <p>3. Enter the 6-digit code above</p>
                <p>4. You'll be logged in successfully</p>
            </div>

            <div class="security-warning">
                <strong>⚠️ Security Reminder:</strong> Never share this OTP with anyone, including REIAC staff. We will never ask for this code via email or phone.
            </div>

            <p>If you didn't attempt to login, please ignore this email and secure your account immediately.</p>

            <p style="color: #999; font-size: 13px; margin-top: 30px;">
                <strong>Need help?</strong> If you continue to have problems, contact the admin support team at your organization.
            </p>

            <div class="footer">
                <p>© {{ date('Y') }} REIAC. All rights reserved.</p>
                <p>This is an automated message. Please do not reply to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>
