<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRole
{
    /**
     * Check if the logged-in admin has one of the required roles.
     *
     * Usage in route: middleware('admin.role:super_admin')
     *                 middleware('admin.role:super_admin,operator')
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin || !in_array($admin->role, $roles)) {
            abort(403, 'Access Denied. You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
