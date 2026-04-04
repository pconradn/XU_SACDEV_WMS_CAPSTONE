<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;
use App\Models\OfficerSubmission;
use App\Models\Organization;
use App\Models\OrganizationSchoolYear;
use App\Models\OrgConstitutionSubmission;
use App\Models\PresidentRegistration;
use App\Models\SchoolYear;
use App\Models\StrategicPlanSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReregHubController extends Controller
{
    public function setSy(Request $request)
    {
        $request->validate([
            'encode_school_year_id' => ['required', 'integer', 'exists:school_years,id'],
        ]);

        $request->session()->put('encode_sy_id', (int) $request->encode_school_year_id);

        return back()->with('status', 'Target school year updated.');
    }

    public function hub(Request $request, Organization $organization)
    {
     
        $encodeSyId = (int) $request->session()->get('encode_sy_id');

        if (!$encodeSyId) {

            $encodeSyId = (int) SchoolYear::query()
                ->where('is_active', true)
                ->value('id');

            if ($encodeSyId) {
                $request->session()->put('encode_sy_id', $encodeSyId);
            }
        }


        $schoolYears = SchoolYear::query()
            ->orderByDesc('id')
            ->get();



        $b1 = StrategicPlanSubmission::query()
            ->where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest('id')
            ->first();

        $b2 = PresidentRegistration::query()
            ->where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest('id')
            ->first();

        $b3 = OfficerSubmission::query()
            ->where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest('id')
            ->first();

        $b5 = ModeratorSubmission::query()
            ->where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest('id')
            ->first();

        $b6 = OrgConstitutionSubmission::query()
            ->where('organization_id', $organization->id)
            ->where('school_year_id', $encodeSyId)
            ->latest('id')
            ->first();


        $forms = [

            'b1' => $this->buildForm(
                label: 'B-1 Strategic Plan',
                model: $b1,
                viewRoute: 'admin.strategic_plans.show',
                routeParamName: 'submission'
            ),

            'b2' => $this->buildForm(
                label: 'B-2 President Registration',
                model: $b2,
                viewRoute: 'admin.b2.president.show',
                routeParamName: 'registration' // FIX HERE
            ),

            'b3' => $this->buildForm(
                label: 'B-3 Officers List',
                model: $b3,
                viewRoute: 'admin.officer_submissions.show',
                routeParamName: 'submission'
            ),

            'b5' => $this->buildForm(
                label: 'B-5 Moderator Submission',
                model: $b5,
                viewRoute: 'admin.moderator_submissions.show',
                routeParamName: 'submission'
            ),

            'b6' => $this->buildForm(
                label: 'B-6 Constitution',
                model: $b6,
                viewRoute: 'admin.constitution.download',
                routeParamName: 'submission'
            ),

        ];

        $allApproved = collect($forms)->every(function ($form) {

            return isset($form['status'])
                && $form['status'] === 'approved_by_sacdev';

        });



        $alreadyActivated = OrganizationSchoolYear::query()
            ->where('organization_id', $organization->id)
            ->where('school_year_id', $encodeSyId)
            ->exists();


        return view('admin.rereg.hub', [

            'organization' => $organization,
            'schoolYears' => $schoolYears,
            'encodeSyId' => $encodeSyId,

            'forms' => $forms,

            'allApproved' => $allApproved,
            'alreadyActivated' => $alreadyActivated,

        ]);
    }





    private function buildForm(
        string $label,
        $model,
        ?string $viewRoute = null,
        string $routeParamName = 'submission'
    ): array {

        $status = $model?->status;

        $badge = $this->statusBadge($status);


        $submittedAtRaw =
            $model->submitted_at
            ?? $model->submitted_to_moderator_at
            ?? $model->forwarded_to_sacdev_at
            ?? $model->created_at
            ?? null;


        $submittedAt = $submittedAtRaw
            ? Carbon::parse($submittedAtRaw)->format('M d, Y — h:i A')
            : null;

        $reviewedAtRaw =
            $model->sacdev_reviewed_at
            ?? $model->moderator_reviewed_at
            ?? null;


        $reviewedAt = $reviewedAtRaw
            ? Carbon::parse($reviewedAtRaw)->format('M d, Y — h:i A')
            : null;


        return [

            'label' => $label,

            'status' => $status,

            'badge' => [
                'text' => $badge['text'],
                'dot'  => $badge['dot'],
                'class'=> $badge['dot'],
            ],

            'viewRoute' => $model && $viewRoute ? $viewRoute : null,

            'routeParams' => $model
                ? [$routeParamName => $model->id]
                : null,


            'meta' => [

                'submitted_at' => $submittedAt,
                'reviewed_at'  => $reviewedAt,

            ],


            'remarksPreview' => $model?->sacdev_remarks
                ?? $model?->moderator_remarks
                ?? null,

        ];
    }


    private function statusBadge(?string $status): array
    {
        return match ($status) {

            'draft' => [
                'text' => 'Draft',
                'dot'  => 'bg-slate-400',
            ],

            'submitted_to_sacdev',
            'submitted' => [
                'text' => 'Submitted to SACDEV',
                'dot'  => 'bg-amber-500',
            ],

            'submitted_to_moderator' => [
                'text' => 'Submitted to Moderator',
                'dot'  => 'bg-amber-500',
            ],

            'returned',
            'returned_by_moderator' => [
                'text' => 'Returned',
                'dot'  => 'bg-rose-500',
            ],

            'approved',
            'approved_by_sacdev' => [
                'text' => 'Approved',
                'dot'  => 'bg-emerald-500',
            ],

            'forwarded_to_sacdev' => [
                'text' => 'Forwarded to SACDEV',
                'dot'  => 'bg-blue-500',
            ],

            default => [
                'text' => 'Not submitted',
                'dot'  => 'bg-slate-400',
            ],
        };
    }


    public function index(Request $request)
    {
        $encodeSyId = (int) $request->session()->get('encode_sy_id');

     
        $activeSy = SchoolYear::query()->where('is_active', true)->first();

       
        if (!$encodeSyId && $activeSy) {
            $encodeSyId = (int) $activeSy->id;
            $request->session()->put('encode_sy_id', $encodeSyId);
        }

       
        $allSchoolYears = SchoolYear::query()->orderByDesc('id')->get();

     
        $schoolYears = collect();
        if ($activeSy) {
            $prevSy = SchoolYear::query()
                ->where('id', '<', $activeSy->id)
                ->orderByDesc('id')
                ->first();

            $nextSy = SchoolYear::query()
                ->where('id', '>', $activeSy->id)
                ->orderBy('id')
                ->first();

            if ($prevSy) $schoolYears->push($prevSy);
            $schoolYears->push($activeSy);
            if ($nextSy) $schoolYears->push($nextSy);
        } else {
            
            $schoolYears = $allSchoolYears->take(3)->reverse()->values();
            if (!$encodeSyId) {
                $encodeSyId = (int) ($schoolYears->last()?->id ?? 0);
                if ($encodeSyId) {
                    $request->session()->put('encode_sy_id', $encodeSyId);
                }
            }
        }

  
        $selectedSy = $encodeSyId
            ? $allSchoolYears->firstWhere('id', $encodeSyId)
            : $activeSy;

      
        $organizations = Organization::query()
            ->orderBy('name')
            ->get();

        $actionable = ['submitted_to_sacdev', 'forwarded_to_sacdev'];

        $caseKeys = collect()
            ->merge(
                StrategicPlanSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => $r->organization_id . '|' . $r->target_school_year_id)
            )
            ->merge(
                PresidentRegistration::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => $r->organization_id . '|' . $r->target_school_year_id)
            )
            ->merge(
                OfficerSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => $r->organization_id . '|' . $r->target_school_year_id)
            )
            ->merge(
                ModeratorSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => $r->organization_id . '|' . $r->target_school_year_id)
            )
            ->unique()
            ->values();

        $syBadges = $caseKeys
            ->map(fn ($k) => (int) explode('|', $k)[1])
            ->countBy()
            ->all();


        $orgBadges = [];
        $readyOrgIds = [];

        if ($encodeSyId) {
    
            $b1 = StrategicPlanSubmission::where('target_school_year_id', $encodeSyId)
                ->get(['id', 'organization_id', 'status'])
                ->sortByDesc('id')
                ->groupBy('organization_id')
                ->map->first();

            $b2 = PresidentRegistration::where('target_school_year_id', $encodeSyId)
                ->get(['id', 'organization_id', 'status'])
                ->sortByDesc('id')
                ->groupBy('organization_id')
                ->map->first();

            $b3 = OfficerSubmission::where('target_school_year_id', $encodeSyId)
                ->get(['id', 'organization_id', 'status'])
                ->sortByDesc('id')
                ->groupBy('organization_id')
                ->map->first();

            $b5 = ModeratorSubmission::where('target_school_year_id', $encodeSyId)
                ->get(['id', 'organization_id', 'status'])
                ->sortByDesc('id')
                ->groupBy('organization_id')
                ->map->first();

            $orgIds = collect()
                ->merge($b1->keys())
                ->merge($b2->keys())
                ->merge($b3->keys())
                ->merge($b5->keys())
                ->unique();

            foreach ($orgIds as $orgId) {
                $statuses = [
                    optional($b1->get($orgId))->status,
                    optional($b2->get($orgId))->status,
                    optional($b3->get($orgId))->status,
                    optional($b5->get($orgId))->status,
                ];

            
                $started = collect($statuses)->filter()->isNotEmpty();
                if (!$started) continue;

                $pendingCount = collect($statuses)
                    ->filter(fn ($s) => in_array($s, $actionable, true))
                    ->count();

                $orgBadges[(int) $orgId] = $pendingCount;

                $nonNullStatuses = collect($statuses)->filter();

                $allApproved = $nonNullStatuses->isNotEmpty() &&
                            $nonNullStatuses->every(fn ($s) => $s === 'approved_by_sacdev');
                if ($allApproved) {
                    $readyOrgIds[] = (int) $orgId;
                }
            }
        }

        $activatedOrgIds = [];

        if ($encodeSyId) {
            $activatedOrgIds = DB::table('organization_school_years')
                ->where('school_year_id', $encodeSyId) // adjust column if different
                ->pluck('organization_id')
                ->map(fn($v) => (int) $v)
                ->all();
        }

        // ================= NEW UNIFIED ORG DATA =================
        $orgData = [];

        foreach ($organizations as $org) {

            $pending = (int)($orgBadges[$org->id] ?? 0);
            $isReady = in_array((int)$org->id, $readyOrgIds ?? [], true);
            $isRegistered = in_array((int)$org->id, $activatedOrgIds ?? [], true);

            $orgData[$org->id] = [
                'pending' => $pending,
                'is_ready' => $isReady,
                'is_registered' => $isRegistered,
            ];
        }

        // ================= SUMMARY =================
        $summary = [
            'total' => $organizations->count(),
            'pending' => collect($orgData)->filter(fn($o) => $o['pending'] > 0)->count(),
            'ready' => collect($orgData)->filter(fn($o) => $o['is_ready'])->count(),
            'registered' => collect($orgData)->filter(fn($o) => $o['is_registered'])->count(),
        ];

        return view('admin.rereg.index', compact(
            'encodeSyId',
            'activeSy',
            'selectedSy',
            'schoolYears',
            'allSchoolYears',
            'organizations',
            'syBadges',
            'orgBadges',
            'readyOrgIds',
            'activatedOrgIds',
            'orgData',
            'summary',
        ));
    }


}
