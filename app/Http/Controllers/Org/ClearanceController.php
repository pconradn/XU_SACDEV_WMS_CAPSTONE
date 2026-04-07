<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClearanceController extends Controller
{

    public function print(Project $project)
    {
        if (!$project->requires_clearance) {
            abort(404);
        }

        $isOutdated = $this->isSnapshotOutdated($project);

        $organization = $project->organization;

        $proposalDoc = $project->documents()
            ->whereHas('formType', fn($q) => $q->where('code','PROJECT_PROPOSAL'))
            ->first();

        $proposal = $proposalDoc?->proposalData;

        $offCampusDoc = $project->documents()
            ->whereHas('formType', fn($q) => $q->where('code','OFF_CAMPUS_APPLICATION'))
            ->first();

        $activity = $offCampusDoc?->offCampus;

        $participants = $activity?->participants ?? collect();

        $totalBudget = $totalBudget = $proposalDoc?->budgetProposal?->total_expenses;


        if (!$project->clearance_snapshot) {

            $snapshot = $this->buildSnapshot($project, $proposal);

            $token = $this->buildToken($snapshot);

            $project->update([
                'clearance_snapshot' => $snapshot,
                'clearance_token' => $token,
                'clearance_status' => 'issued',
                'clearance_issued_at' => now(),
            ]);

        } else {
            $snapshot = $project->clearance_snapshot;
            $token = $project->clearance_token;
        }

        $verificationUrl = route('clearance.verify', [
            'reference' => $project->clearance_reference,
            'token' => $token
        ]);

        return view(
            'org.projects.clearance.print',
            compact(
                'project',
                'organization',
                'proposal',
                'activity',
                'participants',
                'snapshot',
                'verificationUrl',
                'isOutdated'
            )
        );
    }

    public function reissue(Project $project)
    {
        if (!$project->requires_clearance) {
            abort(404);
        }

        $project->update([
            'clearance_snapshot' => null,
            'clearance_token' => null,
            'clearance_status' => 'replaced',
        ]);

        return redirect()
            ->route('org.projects.clearance.print', $project)
            ->with('success', 'Clearance reset. Please print the updated version.');
    }

    public function isSnapshotOutdated(Project $project): bool
    {
        if (!$project->clearance_snapshot) return false;

        $snapshot = $project->clearance_snapshot;

        $proposalDoc = $project->documents()
            ->whereHas('formType', fn($q) => $q->where('code','PROJECT_PROPOSAL'))
            ->first();

        $proposal = $proposalDoc?->proposalData;

        $budgetDoc = $project->documents()
            ->whereHas('formType', fn($q) => $q->where('code','BUDGET_PROPOSAL'))
            ->with('budgetProposal')
            ->first();

        $currentBudget = $budgetDoc?->budgetProposal?->total_expenses;

        return (
            ($snapshot['start_date'] ?? null) != optional($proposal)->start_date ||
            ($snapshot['end_date'] ?? null) != optional($proposal)->end_date ||
            ($snapshot['off_campus_venue'] ?? null) != optional($proposal)->off_campus_venue ||
            ($snapshot['total_budget'] ?? null) != $currentBudget
        );
    }


    public function verify(Request $request, $reference)
    {
        $project = Project::with(['organization'])
            ->where('clearance_reference', $reference)
            ->firstOrFail();

        $snapshot = $project->clearance_snapshot;

        if (!$snapshot) {

            $proposalDoc = $project->documents()
                ->whereHas('formType', fn($q) => $q->where('code','PROJECT_PROPOSAL'))
                ->first();

            $proposal = $proposalDoc?->proposalData;

            $totalBudget = $project->documents()
                ->whereHas('formType', fn($q) => $q->where('code','BUDGET_PROPOSAL'))
                ->with('budgetProposal')
                ->first()?->budgetProposal?->total_expenses;

            $snapshot = [
                'reference' => $project->clearance_reference,
                'title' => $project->title,
                'organization' => $project->organization->name ?? null,
                'start_date' => optional($proposal)->start_date,
                'end_date' => optional($proposal)->end_date,
                'start_time' => optional($proposal)->start_time,
                'end_time' => optional($proposal)->end_time,
                'off_campus_venue' => optional($proposal)->off_campus_venue,
                'issued_at' => null,
                'total_budget' => $totalBudget,
            ];
        }

        $storedToken = $project->clearance_token;
        $token = $request->query('token');

        $isValid = false;

        if ($storedToken && $token) {
            $isValid = hash_equals($storedToken, $token);
        }

        $isRevoked = $project->clearance_status === 'revoked';
        $isReplaced = $project->clearance_status === 'replaced';

        return view('public.clearance.verify', [
            'project' => $project,
            'snapshot' => $snapshot,
            'isValid' => $isValid,
            'isRevoked' => $isRevoked,
            'isReplaced' => $isReplaced,
            'token' => $token
        ]);
    }


    public function upload(Request $request, Project $project)
    {
        if (!$project->requires_clearance) {
            abort(404);
        }

        if ($project->clearance_status === 'verified') {
            return back()->with(
                'error',
                'Clearance already verified. Upload is locked.'
            );
        }

        $request->validate([
            'clearance_file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240'
            ]
        ]);

        if ($project->clearance_file_path) {
            Storage::disk('public')->delete($project->clearance_file_path);
        }

        $path = $request->file('clearance_file')->store(
            'clearances',
            'public'
        );

        $project->update([
            'clearance_file_path' => $path,
            'clearance_status' => 'uploaded'
        ]);

        return back()->with(
            'success',
            'Clearance uploaded successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function buildSnapshot(Project $project, $proposal): array
    {
        $proposalDoc = $project->documents()
            ->whereHas('formType', fn($q) => $q->where('code','PROJECT_PROPOSAL'))
            ->with(['signatures.user'])
            ->first();
        
        $totalBudget = $project->documents()
            ->whereHas('formType', fn($q) => $q->where('code','BUDGET_PROPOSAL'))
            ->with('budgetProposal')
            ->first()?->budgetProposal?->total_expenses;

        $signatures = $proposalDoc?->signatures
            ? $proposalDoc->signatures->map(function ($sig) {
                return [
                    'role' => $sig->role,
                    'name' => $sig->user->name ?? '—',
                    'status' => $sig->status,
                    'signed_at' => $sig->signed_at,
                ];
            })->values()->toArray()
            : [];

        return [
            'reference' => $project->clearance_reference,
            'project_id' => $project->id,
            'title' => $project->title,
            'organization' => $project->organization->name ?? null,

            'start_date' => optional($proposal)->start_date,
            'end_date' => optional($proposal)->end_date,
            'start_time' => optional($proposal)->start_time,
            'end_time' => optional($proposal)->end_time,

            'on_campus_venue' => optional($proposal)->on_campus_venue,
            'off_campus_venue' => optional($proposal)->off_campus_venue,

            'signatories' => $signatures,

            'issued_at' => now()->toDateTimeString(),
            'total_budget' => $totalBudget,
        ];
    }

    private function buildToken(array $snapshot): string
    {
        $payload = implode('|', [
            $snapshot['reference'],
            $snapshot['project_id'],
            $snapshot['title'],
            $snapshot['start_date'],
            $snapshot['end_date'],
            $snapshot['issued_at'],
        ]);

        return hash_hmac('sha256', $payload, config('app.key'));
    }

}