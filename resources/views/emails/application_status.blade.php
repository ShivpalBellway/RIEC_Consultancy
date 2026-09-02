<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Status Update</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f5f7fb;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #eef2f6;
        }
        .header {
            background-color: #1a2f5e;
            padding: 30px;
            text-align: center;
            border-bottom: 4px solid #c89b2a;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            margin: 0;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .header p {
            color: #c89b2a;
            margin: 5px 0 0 0;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .content {
            padding: 36px 30px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #1a2f5e;
            margin-bottom: 12px;
        }
        .intro-text {
            font-size: 14px;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 28px;
        }
        .status-card {
            background: linear-gradient(135deg, #1a2f5e 0%, #243d7a 100%);
            border-radius: 14px;
            padding: 28px;
            text-align: center;
            margin-bottom: 28px;
        }
        .status-card .status-label-title {
            font-size: 11px;
            font-weight: 700;
            color: #c89b2a;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .status-card .status-value {
            font-size: 22px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 0.5px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            background: #f8fafc;
            border-radius: 12px;
            overflow: hidden;
        }
        .details-table td {
            padding: 12px 16px;
            font-size: 13px;
            border-bottom: 1px solid #eef2f6;
        }
        .details-table td.label {
            font-weight: 700;
            color: #64748b;
            width: 40%;
        }
        .details-table td.value {
            color: #0f172a;
            font-weight: 600;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .note {
            font-size: 13px;
            color: #64748b;
            line-height: 1.7;
            background-color: #f0f9ff;
            border-left: 4px solid #1a2f5e;
            padding: 14px 16px;
            border-radius: 0 10px 10px 0;
            margin-bottom: 10px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>REIAC</h1>
            <p>Application Status Update</p>
        </div>

        <div class="content">
            <div class="greeting">Dear {{ $application->name }},</div>

            <p class="intro-text">
                We would like to inform you that the status of your application has been updated.
                Please review the details below.
            </p>

            {{-- Current Status Highlight --}}
            <div class="status-card">
                <div class="status-label-title">Current Status</div>
                <div class="status-value">{{ $statusLabel }}</div>
            </div>

            {{-- Application Details --}}
            <table class="details-table">
                <tr>
                    <td class="label">Applicant Name</td>
                    <td class="value">{{ $application->name }}</td>
                </tr>
                <tr>
                    <td class="label">Application ID</td>
                    <td class="value">#APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="label">Program</td>
                    <td class="value">{{ $application->program?->name ?? 'N/A' }} ({{ $application->program?->country ?? 'N/A' }})</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="value">{{ $application->email }}</td>
                </tr>
                <tr>
                    <td class="label">Updated On</td>
                    <td class="value">{{ now()->format('M d, Y h:i A') }}</td>
                </tr>
            </table>

            <div class="note">
                If you have any questions or need assistance, please do not hesitate to contact our team.
                We are here to help you every step of the way.
            </div>
        </div>

        <div class="footer">
            This is an automated notification from your Consultancy Management Portal.<br>
            © {{ date('Y') }} REIAC. All rights reserved.
        </div>
    </div>
</body>
</html>
