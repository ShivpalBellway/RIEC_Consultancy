<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access admin panel.');
        }

        $isActiveAdmin = Admin::query()
            ->whereKey(session('admin_id'))
            ->where('is_active', true)
            ->exists();

        if (!$isActiveAdmin) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', 'Your admin account is inactive. Please contact the system administrator.');
        }

        return $next($request);
    }
}
