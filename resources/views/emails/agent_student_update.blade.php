<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REIAC Global – Agent Portal Update</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            padding: 30px 15px;
        }
        .wrapper { max-width: 620px; margin: 0 auto; }

        /* Header */
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
        .header .logo-text span {
            color: #dca737;
        }
        .header .tagline {
            font-size: 11px;
            color: rgba(255,255,255,0.65);
            margin-top: 4px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Body card */
        .card {
            background: #ffffff;
            padding: 32px;
            border-left: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
        }

        /* Action badge */
        .badge-wrap { text-align: center; margin-bottom: 24px; }
        .badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .badge.status_updated      { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge.document_verified   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge.document_rejected   { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
        .badge.university_assigned { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }

        /* Greeting */
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

        /* Detail box */
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

        /* CTA button */
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
        .cta-btn span {
            color: #dca737;
        }

        /* Alert box */
        .alert-box {
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .alert-box.warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
        .alert-box.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-box.error   { background: #fff1f2; border: 1px solid #fecdd3; color: #9f1239; }
        .alert-box.info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }

        /* Footer */
        .footer {
            background: #1a2f5e;
            border-radius: 0 0 16px 16px;
            padding: 20px 32px;
            text-align: center;
        }
        .footer p { font-size: 11px; color: rgba(255,255,255,0.5); line-height: 1.7; }
        .footer strong { color: #dca737; }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <div class="logo-text">REIAC <span>Global</span></div>
        <div class="tagline">Agent Partner Portal — Official Notification</div>
    </div>

    <!-- Card -->
    <div class="card">

        <!-- Badge -->
        <div class="badge-wrap">
            <span class="badge {{ $actionType }}">{{ $actionTitle }}</span>
        </div>

        <!-- Greeting -->
        <p class="greeting">Dear {{ $agentName }},</p>
        <p class="msg-text">{{ $message }}</p>

        <!-- Details Table -->
        @if(!empty($details))
        <div class="detail-box">
            <div class="detail-header">📋 Update Details</div>
            <table>
                @foreach($details as $label => $value)
                <tr>
                    <td class="label">{{ ucfirst(str_replace('_', ' ', $label)) }}</td>
                    <td class="value">{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        <!-- Alert based on action type -->
        @if($actionType === 'document_rejected')
        <div class="alert-box error">
            <strong>⚠️ Action Required:</strong> Please log into your portal, navigate to the student's profile, and re-upload the corrected document. Contact REIAC admin if you need guidance.
        </div>
        @elseif($actionType === 'document_verified')
        <div class="alert-box success">
            <strong>✅ Good News:</strong> This document has been successfully verified by our admin team. No further action needed for this document.
        </div>
        @elseif($actionType === 'university_assigned')
        <div class="alert-box info">
            <strong>🏛️ Next Step:</strong> The student will now enter the Offer Letter phase. Please ensure the student is aware of this development.
        </div>
        @elseif($actionType === 'status_updated')
        <div class="alert-box warning">
            <strong>📋 Status Updated:</strong> Please log into your agent portal to view the complete details and any additional steps required for this phase.
        </div>
        @endif

        <!-- CTA Button -->
        <div class="cta-wrap">
            <a href="{{ $portalLink }}" class="cta-btn">
                View Student in <span>Portal</span> &rarr;
            </a>
        </div>

        <p style="font-size: 12px; color: #94a3b8; text-align: center;">
            If you did not expect this email or have any questions, please contact REIAC Global admin team directly.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            This is an automated notification from <strong>REIAC Global Agent Portal</strong>.<br>
            &copy; {{ date('Y') }} REIAC Global Consultancy. All rights reserved.
        </p>
    </div>

</div>
</body>
</html>
