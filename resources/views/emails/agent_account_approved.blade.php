<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved – REIAC Global</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            padding: 30px 15px;
        }
        .wrapper { max-width: 620px; margin: 0 auto; }

        .header {
            background: linear-gradient(135deg, #1a2f5e 0%, #2b4d97 100%);
            border-radius: 16px 16px 0 0;
            padding: 28px 32px;
            text-align: center;
        }
        .header .logo-text {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 1px;
        }
        .header .logo-text span { color: #dca737; }
        .header .tagline {
            font-size: 11px;
            color: rgba(255,255,255,0.65);
            margin-top: 4px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .card {
            background: #ffffff;
            padding: 32px;
            border-left: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
        }

        .greeting {
            font-size: 16px;
            color: #1e293b;
            margin-bottom: 12px;
            font-weight: 600;
        }
        .msg-text {
            font-size: 14px;
            line-height: 1.75;
            color: #475569;
            margin-bottom: 24px;
        }

        .detail-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .detail-box .detail-header {
            background: #f1f5f9;
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-box table { width: 100%; border-collapse: collapse; }
        .detail-box td {
            padding: 10px 16px;
            font-size: 13px;
            vertical-align: top;
            border-bottom: 1px solid #f1f5f9;
        }
        .detail-box tr:last-child td { border-bottom: none; }
        .detail-box td.label {
            font-weight: 700;
            color: #64748b;
            width: 38%;
            white-space: nowrap;
        }
        .detail-box td.value {
            color: #1e293b;
            font-weight: 500;
        }

        .alert-box {
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .alert-box.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .cta-wrap { text-align: center; margin-bottom: 28px; }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #1a2f5e, #2b4d97);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .cta-btn span { color: #dca737; }

        .footer {
            background: #1a2f5e;
            border-radius: 0 0 16px 16px;
            padding: 20px 32px;
            text-align: center;
        }
        .footer p {
            font-size: 11px;
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
        }
        .footer strong { color: #dca737; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="logo-text">REIAC <span>Global</span></div>
        <div class="tagline">Agent Partner Portal</div>
    </div>

    <div class="card">
        <p class="greeting">Dear {{ $agent->name }},</p>
        <p class="msg-text">
            Great news! Your agent account has been <strong>approved</strong> by the REIAC Global admin team.
            You now have full access to the agent portal and can start managing student applications.
        </p>

        <div class="alert-box success">
            <strong>✅ Account Status:</strong> Active — You can now log in and access all features.
        </div>

        <div class="detail-box">
            <div class="detail-header">📋 Account Details</div>
            <table>
                <tr>
                    <td class="label">Agent Name</td>
                    <td class="value">{{ $agent->name }}</td>
                </tr>
                <tr>
                    <td class="label">Agency Name</td>
                    <td class="value">{{ $agent->agency_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="value">{{ $agent->email }}</td>
                </tr>
                <tr>
                    <td class="label">Status</td>
                    <td class="value" style="color: #15803d; font-weight: 700;">Active</td>
                </tr>
            </table>
        </div>

        <div class="cta-wrap">
            <a href="{{ url('/agent/login') }}" class="cta-btn">
                Go to <span>Agent Portal</span> &rarr;
            </a>
        </div>

        <p style="font-size: 12px; color: #94a3b8; text-align: center;">
            If you have any questions, please contact the REIAC Global admin team.
        </p>
    </div>

    <div class="footer">
        <p>
            This is an automated notification from <strong>REIAC Global Agent Portal</strong>.<br>
            &copy; {{ date('Y') }} REIAC Global Consultancy. All rights reserved.
        </p>
    </div>

</div>
</body>
</html>
