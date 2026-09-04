<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentNotification;
use App\Mail\AgentAccountApprovedMail;
use App\Mail\AgentSuspendedMail;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'suspension_reason' => ['nullable', 'string', 'max:1000'],
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

            // Send approval email to agent
            try {
                Mail::to($agent->email)->send(new AgentAccountApprovedMail($agent));
            } catch (\Exception $e) {
                \Log::error('Failed to send agent approval email: ' . $e->getMessage());
            }
        }

        if ($newStatus === 'suspended' && $oldStatus !== 'suspended') {
            $reason = $request->input('suspension_reason', 'Your account has been suspended due to policy violation.');

            // Save suspension reason
            $agent->update(['suspension_reason' => $reason]);

            // Notify Agent in-app
            AgentNotification::create([
                'agent_id' => $agent->id,
                'type'     => 'account_suspended',
                'title'    => 'Account Suspended',
                'message'  => "Your agent account has been suspended. Reason: {$reason}",
                'link'     => route('agent.dashboard'),
            ]);

            // Send suspension email to agent
            try {
                Mail::to($agent->email)->send(new AgentSuspendedMail($agent, $reason));
            } catch (\Exception $e) {
                \Log::error('Failed to send agent suspension email: ' . $e->getMessage());
            }
        }

        $this->log('update_status', 'agents', "Updated agent status for {$agent->name} from {$oldStatus} to {$newStatus}");

        return back()->with('success', "Agent '{$agent->name}' status updated to " . ucfirst($newStatus) . ".");
    }
}
