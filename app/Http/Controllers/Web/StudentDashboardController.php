<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $applications = Auth::user()
            ->applications()
            ->with('program')
            ->latest()
            ->paginate(10);

        return view('pages.web.student.dashboard', compact('applications'));
    }

    public function show(Application $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        $application->load('program');

        return view('pages.web.student.application-show', compact('application'));
    }

    public function downloadAttachment(Application $application, string $fieldKey)
    {
        abort_unless($application->user_id === Auth::id(), 403);

        $attachment = $application->storedAttachment($fieldKey);

        abort_unless($attachment && Storage::disk('local')->exists($attachment['path']), 404);

        return Storage::disk('local')->download($attachment['path'], $attachment['name'], [
            'Cache-Control' => 'private, no-store, max-age=0',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
