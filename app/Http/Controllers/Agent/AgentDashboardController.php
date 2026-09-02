<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\AgentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentDashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('agent')->user();

        // 1. Dashboard Statistics
        $totalStudents = Student::where('agent_id', $agent->id)->count();
        $activeApplications = Student::where('agent_id', $agent->id)->whereNotIn('status', ['completed'])->count();
        $underReview = Student::where('agent_id', $agent->id)->where('status', 'under_review')->count();
        $approvedCount = Student::where('agent_id', $agent->id)->where('status', 'completed')->count();

        // Recent Students
        $recentStudents = Student::where('agent_id', $agent->id)
            ->latest()
            ->take(5)
            ->get();

        // Recent Notifications
        $notifications = AgentNotification::where('agent_id', $agent->id)
            ->latest()
            ->take(5)
            ->get();

        return view('pages.agent.dashboard', compact(
            'totalStudents',
            'activeApplications',
            'underReview',
            'approvedCount',
            'recentStudents',
            'notifications'
        ));
    }
}
