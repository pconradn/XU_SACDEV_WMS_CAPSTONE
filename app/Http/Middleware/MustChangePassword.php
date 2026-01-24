<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Allow logout and the forced password change routes to avoid redirect loops
        $allowedRouteNames = [
            'password.force.form',
            'password.force.update',
            'logout',
        ];

        if ($user->must_change_password) {
            if ($request->route() && in_array($request->route()->getName(), $allowedRouteNames, true)) {
                return $next($request);
            }

            return redirect()->route('password.force.form');
        }

        return $next($request);
    }
}
