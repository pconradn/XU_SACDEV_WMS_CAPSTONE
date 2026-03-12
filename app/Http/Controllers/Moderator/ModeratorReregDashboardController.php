<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;
use App\Models\OrganizationSchoolYear;
use App\Models\OrgConstitutionSubmission;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
use App\Models\StrategicPlanSubmission;
use Illuminate\Http\Request;

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

        /*
        |--------------------------------------------------------------------------
        | Ensure moderator access
        |--------------------------------------------------------------------------
        */

        $isAssignedModerator = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'moderator')
            ->exists();

        abort_unless($isAssignedModerator, 403, 'Moderator access only.');



        $presidentMembership = OrgMembership::query()
            ->with('user')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'president')
            ->first();



        $b1 = StrategicPlanSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->first();



        $b2 = \App\Models\PresidentRegistration::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->first();



        $b3 = \App\Models\OfficerSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->first();



        $b5 = ModeratorSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->first();


        $constitutionSubmission = OrgConstitutionSubmission::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Previous B5 reuse logic
        |--------------------------------------------------------------------------
        */

        $canUsePreviousB5 = false;
        $previousB5SyId = null;

        $prevSyId = $this->previousSyId($syId);

        if ($prevSyId) {

            $hasPrev = ModeratorSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $prevSyId)
                ->exists();

            $editable = ! $b5 || ! in_array($b5->status, [
                'submitted_to_sacdev',
                'approved_by_sacdev'
            ], true);

            $sameModeratorBoth = OrgMembership::query()
                ->where('user_id', $userId)
                ->where('organization_id', $orgId)
                ->whereIn('school_year_id', [$syId, $prevSyId])
                ->where('role', 'moderator')
                ->count() === 2;

            $canUsePreviousB5 = $hasPrev && $editable && $sameModeratorBoth;

            $previousB5SyId = $canUsePreviousB5 ? $prevSyId : null;
        }


        $isActivated = OrganizationSchoolYear::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->exists();



        return view('moderator.rereg.dashboard', compact(

            'b1',
            'b2',
            'b3',
            'b5',

            'constitutionSubmission', 

            'presidentMembership',

            'canUsePreviousB5',
            'previousB5SyId',

            'orgId',
            'syId',

            'isActivated',
        ));
    }


}
