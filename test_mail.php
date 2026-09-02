<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$app->boot();

use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusMail;
use App\Models\Application;

try {
    $application = Application::first();

    if (!$application) {
        echo "No application found in DB.\n";
        exit(1);
    }

    echo "Sending to: " . $application->email . "\n";
    echo "App ID: #APP-" . str_pad($application->id, 5, '0', STR_PAD_LEFT) . "\n";

    Mail::to($application->email)->send(new ApplicationStatusMail($application, 'Received'));

    echo "✅ Mail sent successfully!\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
