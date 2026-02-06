<?php

namespace App\Http\Controllers\Admin;

use App\Models\SchoolYear;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\OfficerSubmission;
use Illuminate\Support\Facades\DB;
use App\Models\ModeratorSubmission;
use App\Http\Controllers\Controller;
use App\Models\PresidentRegistration;
use App\Models\StrategicPlanSubmission;

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

        // Fallback: default to active SY if none selected yet
        // (If your school_years table doesn't have is_active, swap this to latest('id')->value('id'))
        if (!$encodeSyId) {
            $activeId = (int) SchoolYear::query()->where('is_active', true)->value('id');
            if ($activeId) {
                $encodeSyId = $activeId;
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

        $forms = [
            'b1' => $this->mapForm(
                label: 'B-1 Strategic Plan',
                model: $b1,
                viewRoute: 'admin.strategic_plans.show',
                routeParamKey: 'submission',
            ),
            'b2' => $this->mapForm(
                label: 'B-2 President Registration',
                model: $b2,
                viewRoute: 'admin.b2.president.show',
                routeParamKey: 'registration',
            ),
            'b3' => $this->mapForm(
                label: 'B-3 Officers List',
                model: $b3,
                viewRoute: 'admin.officer_submissions.show',
                routeParamKey: 'submission',
            ),
            'b5' => $this->mapForm(
                label: 'B-5 Moderator Submission',
                model: $b5,
                viewRoute: 'admin.moderator_submissions.show',
                routeParamKey: 'submission',
            ),
        ];

        // Optional for later UI: readiness badge
        $allApproved = collect([$b1, $b2, $b3, $b5])->every(function ($m) {
            return $m && (string) $m->status === 'approved_by_sacdev';
        });

        return view('admin.rereg.hub', compact(
            'organization',
            'schoolYears',
            'encodeSyId',
            'forms',
            'allApproved'
        ));
    }

    private function mapForm(string $label, $model, string $viewRoute, string $routeParamKey): array
    {
        $status = $model->status ?? null;

        $badge = match ($status) {
            null => ['text' => 'Not started', 'class' => 'bg-slate-100 text-slate-700'],
            'draft' => ['text' => 'Draft', 'class' => 'bg-slate-100 text-slate-700'],

            'submitted', 'submitted_to_sacdev', 'submitted_to_moderator' =>
                ['text' => 'Submitted', 'class' => 'bg-blue-100 text-blue-800'],

            'forwarded', 'forwarded_to_sacdev' =>
                ['text' => 'Forwarded', 'class' => 'bg-indigo-100 text-indigo-800'],

            'returned', 'returned_by_sacdev', 'returned_by_moderator' =>
                ['text' => 'Returned', 'class' => 'bg-amber-100 text-amber-800'],

            'approved_by_sacdev' =>
                ['text' => 'Approved', 'class' => 'bg-emerald-100 text-emerald-800'],

            default => [
                'text' => ucfirst(str_replace('_', ' ', (string) $status)),
                'class' => 'bg-slate-100 text-slate-700'
            ],
        };

        return [
            'label' => $label,
            'badge' => $badge,
            'viewRoute' => $model ? $viewRoute : null,
            'routeParams' => $model ? [$routeParamKey => $model->id] : [],
            'remarksPreview' => $model->sacdev_remarks
                ?? $model->moderator_remarks
                ?? $model->remarks
                ?? null,
            'meta' => [
                'submitted_at' => optional($model?->submitted_at)?->format('M d, Y h:i A'),
                'reviewed_at'  => optional($model?->sacdev_reviewed_at)?->format('M d, Y h:i A'),
            ],
        ];
    }

    private function actionableStatuses(): array
    {
        return ['submitted_to_sacdev', 'forwarded_to_sacdev'];
    }

    /**
     * SY badges: counts of unique (org_id|sy_id) "cases" that have ANY actionable form, across ALL school years.
     * Returns: [syId => count]
     */
    private function syBadgesAll(): array
    {
        $actionable = $this->actionableStatuses();

        $pairs = collect()
            ->merge(
                StrategicPlanSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn($r) => $r->organization_id . '|' . $r->target_school_year_id)
            )
            ->merge(
                PresidentRegistration::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn($r) => $r->organization_id . '|' . $r->target_school_year_id)
            )
            ->merge(
                OfficerSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn($r) => $r->organization_id . '|' . $r->target_school_year_id)
            )
            ->merge(
                ModeratorSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn($r) => $r->organization_id . '|' . $r->target_school_year_id)
            )
            ->unique()
            ->values();

        // countBy SY id
        return $pairs
            ->map(fn($k) => (int) explode('|', $k)[1])
            ->countBy()
            ->all();
    }

    /**
     * Org badge: for THIS org + THIS target SY, how many forms are actionable.
     * Returns: int 0..4
     */
    private function orgPendingCountFor(Organization $org, int $syId): int
    {
        $actionable = $this->actionableStatuses();

        $statuses = [
            optional(StrategicPlanSubmission::where('organization_id', $org->id)
                ->where('target_school_year_id', $syId)->latest('id')->first(['status']))->status,

            optional(PresidentRegistration::where('organization_id', $org->id)
                ->where('target_school_year_id', $syId)->latest('id')->first(['status']))->status,

            optional(OfficerSubmission::where('organization_id', $org->id)
                ->where('target_school_year_id', $syId)->latest('id')->first(['status']))->status,

            optional(ModeratorSubmission::where('organization_id', $org->id)
                ->where('target_school_year_id', $syId)->latest('id')->first(['status']))->status,
        ];

        return collect($statuses)->filter(fn($s) => in_array($s, $actionable, true))->count();
    }


    public function index(Request $request)
    {
        $encodeSyId = (int) $request->session()->get('encode_sy_id');

        // --- Active SY (needed for Prev/Active/Next buttons + default selection) ---
        $activeSy = SchoolYear::query()->where('is_active', true)->first();

        // Fallback to active SY if none selected
        if (!$encodeSyId && $activeSy) {
            $encodeSyId = (int) $activeSy->id;
            $request->session()->put('encode_sy_id', $encodeSyId);
        }

        // Full list for modal
        $allSchoolYears = SchoolYear::query()->orderByDesc('id')->get();

        // Quick list (Prev / Active / Next)
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
            // Fallback if somehow no active SY exists: show latest 3 as quick buttons
            $schoolYears = $allSchoolYears->take(3)->reverse()->values();
            if (!$encodeSyId) {
                $encodeSyId = (int) ($schoolYears->last()?->id ?? 0);
                if ($encodeSyId) {
                    $request->session()->put('encode_sy_id', $encodeSyId);
                }
            }
        }

        // If selected SY isn't in quick buttons, keep it (modal can still show it),
        // but we need the selected label.
        $selectedSy = $encodeSyId
            ? $allSchoolYears->firstWhere('id', $encodeSyId)
            : $activeSy;

        // Orgs list (you can filter later; right now show all)
        $organizations = Organization::query()
            ->orderBy('name')
            ->get();

        $actionable = ['submitted_to_sacdev', 'forwarded_to_sacdev'];

        // --- SY badges (counts per SY across all orgs) ---
        // unique "case key" = org_id|sy_id where ANY form is actionable
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

        // --- ORG badges (for currently selected SY only) ---
        // count how many of B1/B2/B3/B5 are actionable for each org
        $orgBadges = [];
        $readyOrgIds = [];

        if ($encodeSyId) {
            // Latest status per org for the selected SY
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

                // only orgs that have started re-reg (any exists)
                $started = collect($statuses)->filter()->isNotEmpty();
                if (!$started) continue;

                $pendingCount = collect($statuses)
                    ->filter(fn ($s) => in_array($s, $actionable, true))
                    ->count();

                $orgBadges[(int) $orgId] = $pendingCount;

                $allApproved = collect($statuses)->every(fn ($s) => $s === 'approved_by_sacdev');
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
        ));
    }


}
