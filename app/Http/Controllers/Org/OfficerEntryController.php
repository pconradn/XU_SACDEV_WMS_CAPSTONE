<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OfficerEntry;
use Illuminate\Http\Request;

class OfficerEntryController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'syId'  => (int) $request->session()->get('encode_sy_id'),
        ];
    }

    public function index(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $officers = OfficerEntry::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('full_name')
            ->get();

        return view('org.officers.index', compact('officers', 'syId'));
    }

    public function create(Request $request)
    {
        ['syId' => $syId] = $this->ctx($request);
        return view('org.officers.create', compact('syId'));
    }

    public function store(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'position'  => ['nullable', 'string', 'max:255'],
        ]);

        OfficerEntry::create([
            'organization_id' => $orgId,
            'school_year_id' => $syId,
            ...$data,
        ]);

        // Sprint 1: “notify admin if officers list updated for ACTIVE SY”
        // We'll implement later (log/email). For now, just status.
        return redirect()->route('org.officers.index')
            ->with('status', 'Officer added.');
    }

    public function edit(Request $request, OfficerEntry $officer)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($officer->organization_id === $orgId && $officer->school_year_id === $syId, 404);

        return view('org.officers.edit', compact('officer', 'syId'));
    }

    public function update(Request $request, OfficerEntry $officer)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($officer->organization_id === $orgId && $officer->school_year_id === $syId, 404);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'position'  => ['nullable', 'string', 'max:255'],
        ]);

        $officer->update($data);

        return redirect()->route('org.officers.index')
            ->with('status', 'Officer updated.');
    }

    public function destroy(Request $request, OfficerEntry $officer)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($officer->organization_id === $orgId && $officer->school_year_id === $syId, 404);

        $officer->delete();

        return redirect()->route('org.officers.index')
            ->with('status', 'Officer deleted.');
    }
}
