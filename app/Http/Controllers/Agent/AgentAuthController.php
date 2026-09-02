<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Services\AgentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentAuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::guard('agent')->check()) {
            return redirect()->route('agent.dashboard');
        }

        return view('pages.agent.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'agency_name'  => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:agents'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'country'      => ['nullable', 'string', 'max:100'],
            'address'      => ['nullable', 'string'],
        ]);

        $agent = Agent::create([
            'name'        => $data['name'],
            'agency_name' => $data['agency_name'],
            'email'       => $data['email'],
            'password'    => $data['password'], // Cast automatically hashes password or model handles it
            'phone'       => $data['phone'] ?? null,
            'country'     => $data['country'] ?? null,
            'address'     => $data['address'] ?? null,
            'status'      => 'pending', // Requires admin approval
        ]);

        // Notify Admin of new agent registration
        AgentNotificationService::notifyAdminAndLog(
            agentName: $agent->name,
            action: 'agent_registered',
            module: 'agent_auth',
            description: "New agent registered: {$agent->name} ({$agent->agency_name}). Requires Admin approval.",
            details: [
                'Agency Name' => $agent->agency_name,
                'Email'       => $agent->email,
                'Phone'       => $agent->phone,
                'Country'     => $agent->country,
            ]
        );

        return redirect()->route('agent.login')
            ->with('success', 'Registration successful! Your account is currently pending Admin approval. You will be able to log in once approved.');
    }

    public function showLogin()
    {
        if (Auth::guard('agent')->check()) {
            return redirect()->route('agent.dashboard');
        }

        return view('pages.agent.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $agent = Agent::where('email', $credentials['email'])->first();

        if (!$agent || !Hash::check($credentials['password'], $agent->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput($request->only('email'));
        }

        if ($agent->status === 'pending') {
            return back()->withErrors(['email' => 'Your agent account is pending admin approval. You will receive an email once approved.'])->withInput($request->only('email'));
        }

        if ($agent->status === 'suspended') {
            return back()->withErrors(['email' => 'Your agent account has been suspended. Please contact system administrator.'])->withInput($request->only('email'));
        }

        Auth::guard('agent')->login($agent, $request->boolean('remember'));
        session(['agent_id' => $agent->id]);

        AgentNotificationService::notifyAdminAndLog(
            agentName: $agent->name,
            action: 'agent_logged_in',
            module: 'agent_auth',
            description: "Agent logged into portal: {$agent->name}"
        );

        return redirect()->route('agent.dashboard')->with('success', 'Welcome back to Agent Dashboard!');
    }

    public function logout(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        if ($agent) {
            AgentNotificationService::notifyAdminAndLog(
                agentName: $agent->name,
                action: 'agent_logged_out',
                module: 'agent_auth',
                description: "Agent logged out: {$agent->name}"
            );
        }

        Auth::guard('agent')->logout();
        $request->session()->forget('agent_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('agent.login')->with('success', 'Logged out successfully.');
    }
}
