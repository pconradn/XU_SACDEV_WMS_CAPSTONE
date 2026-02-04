<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SchoolYear;
use App\Models\StrategicPlanSubmission;
use App\Models\PresidentRegistration;
use App\Models\OfficerSubmission;
use App\Models\ModeratorSubmission;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        $encodeSyId = (int) $request->session()->get('encode_sy_id');

        // Fallback to active SY if none selected
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

        // For now show all orgs; later you can filter to orgs that have submissions for this SY
        $organizations = Organization::query()
            ->orderBy('name')
            ->get();

        return view('admin.rereg.index', compact(
            'schoolYears',
            'encodeSyId',
            'organizations'
        ));
    }
}
