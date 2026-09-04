<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Suspended – REIAC Global</title>
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

        .reason-box {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
        }
        .reason-box .reason-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #9f1239;
            margin-bottom: 8px;
        }
        .reason-box .reason-text {
            font-size: 14px;
            color: #1e293b;
            line-height: 1.6;
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
        .alert-box.error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
        }

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
            We regret to inform you that your agent account has been <strong>suspended</strong> by the REIAC Global admin team.
            Your access to the agent portal has been restricted until further notice.
        </p>

        <div class="alert-box error">
            <strong>⚠️ Account Status:</strong> Suspended — Please contact the admin team for further clarification.
        </div>

        <div class="reason-box">
            <div class="reason-label">🔴 Reason for Suspension</div>
            <div class="reason-text">{{ $reason }}</div>
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
                    <td class="value" style="color: #be123c; font-weight: 700;">Suspended</td>
                </tr>
            </table>
        </div>

        <p style="font-size: 12px; color: #94a3b8; text-align: center;">
            If you believe this is a mistake or have any questions, please contact the REIAC Global admin team directly.
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
