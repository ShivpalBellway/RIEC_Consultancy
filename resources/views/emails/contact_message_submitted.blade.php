<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
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
            padding: 30px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 800;
            color: #1a2f5e;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #eef2f6;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 10px 0;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
            vertical-align: top;
        }
        .data-table td.label {
            font-weight: 700;
            color: #64748b;
            width: 35%;
        }
        .data-table td.value {
            color: #0f172a;
            width: 65%;
        }
        .message-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            color: #0f172a;
            font-size: 14px;
            line-height: 1.7;
            white-space: pre-line;
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
            <p>New Contact Message</p>
        </div>

        <div class="content">
            <div class="section-title">Contact Information</div>
            <table class="data-table">
                <tr>
                    <td class="label">Name</td>
                    <td class="value" style="font-weight: bold;">{{ $contactMessage->name }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="value">
                        <a href="mailto:{{ $contactMessage->email }}" style="color: #1a2f5e; text-decoration: none;">{{ $contactMessage->email }}</a>
                    </td>
                </tr>
                <tr>
                    <td class="label">Phone</td>
                    <td class="value">{{ $contactMessage->phone ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Subject</td>
                    <td class="value">{{ $contactMessage->subject ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Submitted On</td>
                    <td class="value">{{ $contactMessage->created_at->format('M d, Y h:i A') }}</td>
                </tr>
            </table>

            <div class="section-title" style="margin-top: 30px;">Message</div>
            <div class="message-box">{{ $contactMessage->message }}</div>
        </div>

        <div class="footer">
            This is an automated notification from your Consultancy Management Portal.<br>
            &copy; {{ date('Y') }} REIAC. All rights reserved.
        </div>
    </div>
</body>
</html>
