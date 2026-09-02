<?php

namespace App\Http\Middleware;

use App\Models\Agent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AgentAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $agent = Auth::guard('agent')->user();

        if (!$agent) {
            // Also check session fallback if any
            if (session()->has('agent_id')) {
                $agent = Agent::find(session('agent_id'));
                if ($agent) {
                    Auth::guard('agent')->login($agent);
                }
            }
        }

        if (!$agent) {
            return redirect()->route('agent.login')
                ->with('error', 'Please login to access the agent dashboard.');
        }

        if ($agent->status === 'pending') {
            Auth::guard('agent')->logout();
            session()->forget('agent_id');

            return redirect()->route('agent.login')
                ->with('error', 'Your agent account is pending admin approval. You will be able to log in once approved.');
        }

        if ($agent->status === 'suspended') {
            Auth::guard('agent')->logout();
            session()->forget('agent_id');

            return redirect()->route('agent.login')
                ->with('error', 'Your agent account has been suspended. Please contact system administrator.');
        }

        return $next($request);
    }
}
