<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // must be logged in
        if (!auth()->check()) {
            abort(403, 'Unauthorized.');
        }

        $user = auth()->user();


        if ($user->hasRole('super_admin')) {
            return $next($request);
        }


        if (!$user->hasPermission($permission)) {
            abort(403, 'You do not have permission.');
        }

        return $next($request);
    }
}