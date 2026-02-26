<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'या कार्यासाठी तुम्हाला अधिकार नाहीत');
        }

        return $next($request);
    }
}
