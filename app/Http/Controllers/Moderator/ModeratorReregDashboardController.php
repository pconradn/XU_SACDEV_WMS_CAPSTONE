<?php

namespace App\Http\Controllers\Moderator;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Models\OrgMembership;
use App\Models\ModeratorSubmission;
use App\Http\Controllers\Controller;
use App\Models\StrategicPlanSubmission;

class ModeratorReregDashboardController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'userId' => (int) auth()->id(),
            'orgId'  => (int) $request->session()->get('active_org_id'),
            'syId'   => (int) $request->session()->get('encode_sy_id'),
        ];
    }

    private function previousSyId(int $currentSyId): ?int
    {
        $current = SchoolYear::query()->find($currentSyId);
        if (! $current) return null;

        return SchoolYear::query()
            ->where('id', '!=', $currentSyId)
            ->when($current->start_date, fn ($q) => $q->where('start_date', '<', $current->start_date))
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->value('id');
    }

    public function index(Request $request)
    {
        ['userId' => $userId, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        if (! $orgId || ! $syId) {
            return redirect()->route('org.home')
                ->with('error', 'Missing active organization or school year in session.');
        }

        
        $isAssignedModerator = \App\Models\OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'moderator')
            ->exists();

        abort_unless($isAssignedModerator, 403, 'Moderator access only.');


        // Who is the president for this org + target SY?
        $presidentMembership = OrgMembership::query()
            ->with('user')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'president')
            ->first();

        // B1 status (Strategic Plan)
        $b1 = null;
        if (class_exists(StrategicPlanSubmission::class)) {
            $b1 = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $syId) // adjust if your column is school_year_id
                ->first();
        }

        // Optional B2/B3 if those models exist
        $b2 = null;
        $b2Class = 'App\\Models\\PresidentRegistration';
        if (class_exists($b2Class)) {
            $b2 = $b2Class::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $syId)
                ->first();
        }

        $b3 = null;
        $b3Class = 'App\\Models\\OfficerSubmission';
        if (class_exists($b3Class)) {
            $b3 = $b3Class::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $syId)
                ->first();
        }

        $b5 = ModeratorSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->first();

        $canUsePreviousB5 = false;
        $previousB5SyId = null;

        $prevSyId = $this->previousSyId($syId);
        if ($prevSyId) {
            $hasPrev = ModeratorSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $prevSyId)
                ->exists();

            $editable = ! $b5 || ! in_array($b5->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true);

            // “same moderator both years” check (update to your real model)
            $sameModeratorBoth = \App\Models\OrgMembership::query()
                ->where('user_id', $userId)
                ->where('organization_id', $orgId)
                ->whereIn('school_year_id', [$syId, $prevSyId])
                ->where('role', 'moderator')
                ->count() === 2;

            $canUsePreviousB5 = $hasPrev && $editable && $sameModeratorBoth;
            $previousB5SyId = $canUsePreviousB5 ? $prevSyId : null;
        }

        return view('moderator.rereg.dashboard', compact(
            'b1',
            'b2',
            'b3',
            'b5',
            'presidentMembership',
            'canUsePreviousB5',
            'previousB5SyId',
            'orgId',
            'syId'
        ));
    }
}
