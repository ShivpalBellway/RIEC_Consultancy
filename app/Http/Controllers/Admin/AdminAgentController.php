<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentNotification;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class AdminAgentController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = Agent::withCount('students');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('agency_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $agents = $query->latest()->paginate(15)->withQueryString();
        $pendingCount = Agent::where('status', 'pending')->count();

        $this->log('view', 'agents', 'Viewed Agents management list');

        return view('pages.admin.agents.index', compact('agents', 'pendingCount'));
    }

    public function updateStatus(Request $request, Agent $agent)
    {
        $request->validate([
            'status' => ['required', 'in:pending,active,suspended'],
        ]);

        $oldStatus = $agent->status;
        $newStatus = $request->status;

        $agent->update(['status' => $newStatus]);

        if ($newStatus === 'active' && $oldStatus !== 'active') {
            // Notify Agent in-app
            AgentNotification::create([
                'agent_id' => $agent->id,
                'type'     => 'account_approved',
                'title'    => 'Account Approved!',
                'message'  => 'Your agent account has been approved by the Admin. You now have full access to the agent portal.',
                'link'     => route('agent.dashboard'),
            ]);
        }

        $this->log('update_status', 'agents', "Updated agent status for {$agent->name} from {$oldStatus} to {$newStatus}");

        return back()->with('success', "Agent '{$agent->name}' status updated to " . ucfirst($newStatus) . ".");
    }
}
