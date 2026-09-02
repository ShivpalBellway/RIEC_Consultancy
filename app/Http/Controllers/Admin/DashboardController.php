<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\EligibilityField;
use App\Models\FormSection;
use App\Models\Application;
use App\Traits\LogsActivity; // ← Add this

class DashboardController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function index()
    {
        $allStatuses = [
            'pending',
            'received',
            'university_applied',
            'tuition_fee_confirmed',
            'visa_applied',
            'visa_granted',
            'visa_rejected',
            'studying',
            'refund_complete',
        ];

        // Count per status
        $statusCounts = [];
        foreach ($allStatuses as $s) {
            $statusCounts[$s] = Application::where('status', $s)->count();
        }

        $stats = [
            'total_programs'         => Program::count(),
            'active_programs'        => Program::where('is_active', true)->count(),
            'total_applications'     => Application::count(),
            'pending_applications'   => $statusCounts['pending'],
            'received_applications'  => $statusCounts['received'],
            'visa_granted'           => $statusCounts['visa_granted'],
            'visa_rejected'          => $statusCounts['visa_rejected'],
            'studying'               => $statusCounts['studying'],
            'refund_complete'        => $statusCounts['refund_complete'],
        ];

        $recent_programs     = Program::latest()->take(5)->get();
        $recent_applications = Application::with('program')->latest()->take(5)->get();

        // Log: Dashboard viewed
        $this->log('view_dashboard', 'dashboard',
            'Admin viewed dashboard. Stats: ' .
            'Total Programs: ' . $stats['total_programs'] . ', ' .
            'Total Applications: ' . $stats['total_applications'] . ', ' .
            'Pending: ' . $stats['pending_applications']
        );

        return view('pages.admin.dashboard', compact('stats', 'statusCounts', 'recent_programs', 'recent_applications'));
    }
}
