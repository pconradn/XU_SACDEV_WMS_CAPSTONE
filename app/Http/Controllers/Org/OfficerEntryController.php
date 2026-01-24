<?php

namespace App\Http\Controllers\Org;

use App\Support\Audit;
use App\Models\SchoolYear;
use App\Models\OfficerEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        $officer=OfficerEntry::create([
            'organization_id' => $orgId,
            'school_year_id' => $syId,
            ...$data,
        ]);


        $this->logOfficerUpdateIfActiveSy($orgId, $syId, 'created', $officer);

        // implement later email 
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
        $old = $officer->only(['full_name','email','position']);

        $officer->update($data);

        $this->logOfficerUpdateIfActiveSy($orgId, $syId, 'updated', $officer);

        return redirect()->route('org.officers.index')
            ->with('status', 'Officer updated.');
    }

    public function destroy(Request $request, OfficerEntry $officer)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($officer->organization_id === $orgId && $officer->school_year_id === $syId, 404);
        
        $this->logOfficerUpdateIfActiveSy($orgId, $syId, 'deleted', $officer);

        $officer->delete();

        return redirect()->route('org.officers.index')
            ->with('status', 'Officer deleted.');
    }


    private function logOfficerUpdateIfActiveSy(int $orgId, int $syId, string $action, $officer): void
    {
        $activeSy = SchoolYear::activeYear();
        if (!$activeSy) return;

        // only log when encoding active SY
        if ($syId !== (int) $activeSy->id) return;

        $name = $officer->full_name ?? 'Unknown';
        $email = $officer->email ?? '';

        $verb = match ($action) {
            'created' => 'Added',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            default => 'Changed',
        };

        Audit::log(
            'officers_updated',
            "{$verb} officer: {$name}" . ($email ? " ({$email})" : ''),
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $orgId,
                'school_year_id' => $syId,
                'meta' => [
                    'action' => $action,
                    'officer_entry_id' => $officer->id ?? null,
                    'email' => $email,
                ],
            ]
        );
    }

}
