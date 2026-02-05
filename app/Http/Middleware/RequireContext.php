<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireContext
{
    public function handle(Request $request, Closure $next)
    {
        
        if ($request->routeIs('context.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        if (! $orgId || ! $syId) {
            return redirect()->route('context.show');
        }

        return $next($request);
    }
}
