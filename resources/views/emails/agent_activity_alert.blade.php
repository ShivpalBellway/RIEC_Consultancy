<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Agent Activity Notification</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { border-bottom: 2px solid #0056b3; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #0056b3; font-size: 22px; }
        .badge { display: inline-block; background: #e3f2fd; color: #0d47a1; font-weight: bold; padding: 5px 12px; border-radius: 4px; font-size: 13px; margin-bottom: 15px; }
        .content { font-size: 15px; line-height: 1.6; color: #444; }
        .details-box { background: #fafafa; border: 1px solid #eee; padding: 15px; border-radius: 6px; margin: 20px 0; }
        .details-box table { width: 100%; border-collapse: collapse; }
        .details-box td { padding: 6px 0; font-size: 14px; }
        .details-box td.label { font-weight: bold; width: 35%; color: #666; }
        .footer { font-size: 12px; color: #888; text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>REIAC Global Admin Alert</h2>
        </div>
        <div class="content">
            <span class="badge">{{ $actionTitle }}</span>
            <p><strong>Agent Name:</strong> {{ $agentName }}</p>
            <p><strong>Description:</strong> {{ $description }}</p>

            @if(!empty($details))
            <div class="details-box">
                <table>
                    @foreach($details as $key => $val)
                    <tr>
                        <td class="label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</td>
                        <td>{{ is_array($val) ? json_encode($val) : $val }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @endif

            <p style="margin-top: 25px;">Please log into the Admin Dashboard to review details if required.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} REIAC Global. Automated System Notification.
        </div>
    </div>
</body>
</html>
