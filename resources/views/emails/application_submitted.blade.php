<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Student Application</title>
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
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .section-title:first-of-type {
            margin-top: 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 10px 0;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
        }
        .data-table td.label {
            font-weight: 700;
            color: #64748b;
            width: 40%;
            vertical-align: top;
        }
        .data-table td.value {
            color: #0f172a;
            width: 60%;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .badge {
            background-color: #f0fdf4;
            color: #166534;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>REIAC</h1>
            <p>New Student Application Details</p>
        </div>

        <div class="content">
            <div class="section-title">General Information</div>
            <table class="data-table">
                <tr>
                    <td class="label">Applicant Name</td>
                    <td class="value" style="font-weight: bold;">{{ $application->name }}</td>
                </tr>
                <tr>
                    <td class="label">Email Address</td>
                    <td class="value"><a href="mailto:{{ $application->email }}" style="color: #1a2f5e; text-decoration: none;">{{ $application->email }}</a></td>
                </tr>
                <tr>
                    <td class="label">Phone Number</td>
                    <td class="value">{{ $application->phone }}</td>
                </tr>
                <tr>
                    <td class="label">Selected Program</td>
                    <td class="value"><strong>{{ $application->program?->name ?? 'N/A' }}</strong> ({{ $application->program?->country ?? 'N/A' }})</td>
                </tr>
                <tr>
                    <td class="label">Submission Date</td>
                    <td class="value">{{ $application->created_at->format('M d, Y h:i A') }}</td>
                </tr>
            </table>

            @if(!empty($application->eligibility_answers))
                <div class="section-title">Eligibility Criteria Answers</div>
                <table class="data-table">
                    @foreach($application->eligibility_answers as $key => $ans)
                        <tr>
                            <td class="label">{{ $ans['label'] ?? $key }}</td>
                            <td class="value">
                                {{ is_array($ans['value']) ? implode(', ', $ans['value']) : ($ans['value'] ?: '—') }}
                                @if(!empty($ans['unit']) && !empty($ans['value']))
                                    {{ $ans['unit'] }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

            @if(!empty($application->form_answers))
                <div class="section-title">Form Information Answers</div>
                <table class="data-table">
                    @foreach($application->form_answers as $key => $ans)
                        <tr>
                            <td class="label">{{ $ans['label'] ?? $key }}</td>
                            <td class="value">
                                @if(!empty($ans['is_file']))
                                    @if(!empty($ans['store_in_system']))
                                        <a href="{{ asset('storage/' . $ans['value']) }}" target="_blank" style="color: #1a2f5e; text-decoration: underline;">
                                            View Stored Attachment
                                        </a>
                                    @else
                                        <span class="badge" style="background-color: #eff6ff; color: #1e40af;">Direct Mail Attachment</span>
                                    @endif
                                @else
                                    {{ is_array($ans['value']) ? implode(', ', $ans['value']) : ($ans['value'] ?: '—') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>

        <div class="footer">
            This is an automated notification from your Consultancy Management Portal.<br>
            © {{ date('Y') }} REIAC. All rights reserved.
        </div>
    </div>
</body>
</html>
