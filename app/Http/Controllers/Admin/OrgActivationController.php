<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;
use App\Models\OfficerEntry;
use App\Models\OfficerSubmission;
use App\Models\Organization;
use App\Models\OrganizationSchoolYear;
use App\Models\OrgConstitutionSubmission;
use App\Models\OrgMembership;
use App\Models\PresidentRegistration;
use App\Models\Project;
use App\Models\StrategicPlanProject;
use App\Models\StrategicPlanSubmission;
use App\Models\User;
use App\Support\AccountProvisioner;
use App\Support\InAppNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrgActivationController extends Controller
{
    public function activate(Request $request, Organization $organization)
    {
        $encodeSyId = (int) $request->session()->get('encode_sy_id', 0);

        abort_unless($encodeSyId > 0, 403, 'Please select a target school year first.');

        // Fetch latest submissions
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


        // Validate ALL approved
        $allApproved = collect([$b1, $b2, $b3, $b5, $b6])
            ->every(fn ($m) => $m && $m->status === 'approved_by_sacdev');

        if (! $allApproved) {
            return back()->with(
                'status',
                'Activation blocked: All B-1, B-2, B-3, B-5, and B-6 must be approved by SAcDev.'
            );
        }


        try {

            DB::transaction(function () use ($organization, $encodeSyId, $b1) {

                // Prevent duplicate activation
                $existing = OrganizationSchoolYear::query()
                    ->where('organization_id', $organization->id)
                    ->where('school_year_id', $encodeSyId)
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    throw new \RuntimeException('ALREADY_ACTIVATED');
                }


                // Create activation record ONLY
                OrganizationSchoolYear::create([
                    'organization_id' => $organization->id,
                    'school_year_id' => $encodeSyId,
                    'president_user_id' => $b1->submitted_by_user_id,
                    'president_confirmed_at' => now(),
                ]);

            });

        } catch (\RuntimeException $e) {

            if ($e->getMessage() === 'ALREADY_ACTIVATED') {

                return back()->with(
                    'status',
                    'This organization is already registered for this school year.'
                );
            }

            throw $e;
        }


        // Notify president (optional but good UX)
        DB::afterCommit(function () use ($organization, $encodeSyId, $b1) {

            if (!$b1) return;

            $president = User::find($b1->submitted_by_user_id);

            if (!$president) return;

            InAppNotifier::notifyOnce($president, [

                'dedupe_key' => "activation:org{$organization->id}:sy{$encodeSyId}",

                'title' => 'Organization Registered',

                'message' =>
                    'Your organization has been successfully registered for the selected school year.',

                'org_id' => $organization->id,

                'target_sy_id' => $encodeSyId,

                'form' => 'activation',

                'status' => 'activated',

                'action_url' => route('org.rereg.index'),

                'send_mail' => true,

            ]);

        });


        return back()->with(
            'success',
            'Organization registered successfully.'
        );
    }

    private function makeXuEmailFromStudentId(?string $studentId): ?string
    {
        $sid = trim((string) $studentId);
        if ($sid === '') return null;

        $digits = preg_replace('/\D+/', '', $sid);
        if ($digits === '') return null;

        return mb_strtolower($digits . '@my.xu.edu.ph');
    }

    private function parseStudentIdFromEmail(?string $email): ?string
    {
        $e = mb_strtolower(trim((string) $email));
        if ($e === '') return null;

        $local = explode('@', $e)[0] ?? '';
        $localDigits = preg_replace('/\D+/', '', $local);

        return $localDigits !== '' ? $localDigits : null;
    }
}
