<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Organization;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $q = AuditLog::query()
            ->with(['actor', 'organization', 'schoolYear'])
            ->latest();

        // Filters (optional)
        if ($request->filled('event')) {
            $q->where('event', $request->string('event'));
        }

        if ($request->filled('organization_id')) {
            $q->where('organization_id', (int)$request->organization_id);
        }

        if ($request->filled('school_year_id')) {
            $q->where('school_year_id', (int)$request->school_year_id);
        }

        $logs = $q->paginate(25)->withQueryString();

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'organizations' => Organization::orderBy('name')->get(),
            'schoolYears' => SchoolYear::orderByDesc('id')->get(),
            'events' => AuditLog::query()->select('event')->distinct()->orderBy('event')->pluck('event'),
        ]);
    }
}
