<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OrgContextMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $orgId = $request->session()->get('active_org_id');
        $syId  = $request->session()->get('encode_sy_id');

        if (!$orgId) {
            return redirect()
                ->route('org.home')
                ->with('status', 'Please select an organization first.');
        }

        if (!$syId) {
            return redirect()
                ->route('org.encode-sy.show')
                ->with('status', 'Please select a school year to encode.');
        }

        return $next($request);
    }
}
