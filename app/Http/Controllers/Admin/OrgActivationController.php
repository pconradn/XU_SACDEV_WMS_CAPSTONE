<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationSchoolYear;
use App\Models\OfficerEntry;
use App\Models\OfficerSubmission;
use App\Models\ModeratorSubmission;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\StrategicPlanProject;
use App\Models\StrategicPlanSubmission;
use App\Models\PresidentRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrgActivationController extends Controller
{
    public function activate(Request $request, Organization $organization)
    {
        $encodeSyId = (int) $request->session()->get('encode_sy_id', 0);
        abort_unless($encodeSyId > 0, 403, 'Please select a target school year first.');

        // Fetch latest submissions for org+SY
        $b1 = StrategicPlanSubmission::query()
            ->where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest('id')->first();

        $b2 = PresidentRegistration::query()
            ->where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest('id')->first();

        $b3 = OfficerSubmission::query()
            ->where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->with('items')
            ->latest('id')->first();

        $b5 = ModeratorSubmission::query()
            ->where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest('id')->first();

        $allApproved = collect([$b1, $b2, $b3, $b5])->every(
            fn ($m) => $m && (string) $m->status === 'approved_by_sacdev'
        );

        if (! $allApproved) {
            return back()->with('status', 'Activation blocked: All B-1, B-2, B-3, and B-5 must be approved by SAcDev.');
        }

        try {
            DB::transaction(function () use ($organization, $encodeSyId, $b1, $b3, $b5) {

                /**
                 * HARD LOCK (DB side)
                 * - lock the org+sy activation row check so simultaneous requests can’t double-create
                 */
                $existing = OrganizationSchoolYear::query()
                    ->where('organization_id', $organization->id)
                    ->where('school_year_id', $encodeSyId)
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    // Throwing an exception cleanly aborts the transaction
                    throw new \RuntimeException('ALREADY_ACTIVATED');
                }

                // ---------------------------
                // A) Update Organization profile (Choice A)
                // ---------------------------
                $organization->update([
                    'name'   => $b1->org_name ?: $organization->name,
                    'acronym'=> $b1->org_acronym ?: $organization->acronym,

                    'mission' => $b1->mission,
                    'vision'  => $b1->vision,
                    'logo_path' => $b1->logo_path,
                    'logo_original_name' => $b1->logo_original_name,
                    'logo_mime' => $b1->logo_mime,
                    'logo_size_bytes' => $b1->logo_size_bytes,

                    // keep if you migrated this
                    'last_b1_submission_id' => $b1->id,
                ]);

                // ---------------------------
                // B) Create/Upsert OfficerEntries from B3 items
                // ---------------------------
                $items = $b3?->items ?? collect();

                foreach ($items as $item) {
                    $studentId = trim((string) $item->student_id_number);
                    $email = $this->makeXuEmailFromStudentId($studentId);

                    OfficerEntry::query()->updateOrCreate(
                        [
                            'organization_id' => $organization->id,
                            'school_year_id' => $encodeSyId,
                            'source_officer_submission_item_id' => $item->id,
                        ],
                        [
                            'full_name' => $item->officer_name,
                            'position' => $item->position,
                            'student_id_number' => $studentId ?: null,
                            'course_and_year' => $item->course_and_year,
                            'latest_qpi' => $item->latest_qpi,
                            'mobile_number' => $item->mobile_number,
                            'sort_order' => $item->sort_order,
                            'email' => $email,
                            'user_id' => null, // do NOT create users here
                        ]
                    );
                }

                // ---------------------------
                // C) Create/Upsert Projects from B1 StrategicPlanProjects
                // ---------------------------
                $spProjects = StrategicPlanProject::query()
                    ->where('submission_id', $b1->id)
                    ->get();

                foreach ($spProjects as $sp) {
                    Project::query()->updateOrCreate(
                        [
                            'organization_id' => $organization->id,
                            'school_year_id' => $encodeSyId,
                            'source_strategic_plan_project_id' => $sp->id,
                        ],
                        [
                            'title' => $sp->title,
                            'category' => $sp->category,
                            'target_date' => $sp->target_date,
                            'implementing_body' => $sp->implementing_body,
                            'budget' => $sp->budget,
                        ]
                    );
                }

                // ---------------------------
                // D) Create activation record (THIS is the "activated" lock)
                // ---------------------------
                OrganizationSchoolYear::query()->create([
                    'organization_id' => $organization->id,
                    'school_year_id' => $encodeSyId,
                    'president_user_id' => (int) $b1->submitted_by_user_id,
                    'president_confirmed_at' => now(),
                ]);

                // ---------------------------
                // E) Link President + Moderator OfficerEntry to their accounts
                // ---------------------------

                // President: B1 submitter
                $presUser = User::find((int) $b1->submitted_by_user_id);

                if ($presUser) {
                    $presEmail = mb_strtolower(trim((string) $presUser->email));

                    $presOfficer = OfficerEntry::query()
                        ->where('organization_id', $organization->id)
                        ->where('school_year_id', $encodeSyId)
                        ->whereRaw('LOWER(email) = ?', [$presEmail])
                        ->first();

                    if ($presOfficer) {
                        if ((int) $presOfficer->user_id !== (int) $presUser->id) {
                            $presOfficer->user_id = $presUser->id;
                            $presOfficer->save();
                        }

                        OrgMembership::query()
                            ->where('organization_id', $organization->id)
                            ->where('school_year_id', $encodeSyId)
                            ->where('role', 'president')
                            ->where('user_id', $presUser->id)
                            ->whereNull('archived_at')
                            ->update(['officer_entry_id' => $presOfficer->id]);
                    }
                }

                // Moderator: B5 has moderator_user_id (+ email)
                if ((int) ($b5->moderator_user_id ?? 0) > 0) {
                    $modUser = User::find((int) $b5->moderator_user_id);
                    $modEmail = mb_strtolower(trim((string) ($b5->email ?: ($modUser?->email ?? ''))));

                    $modOfficer = OfficerEntry::query()
                        ->where('organization_id', $organization->id)
                        ->where('school_year_id', $encodeSyId)
                        ->whereRaw('LOWER(email) = ?', [$modEmail])
                        ->first();

                    if (! $modOfficer) {
                        $modOfficer = OfficerEntry::create([
                            'organization_id' => $organization->id,
                            'school_year_id' => $encodeSyId,
                            'full_name' => $b5->full_name ?: ($modUser?->name ?? 'Moderator'),
                            'position' => 'Moderator',
                            'email' => $modEmail ?: null,
                            'mobile_number' => $b5->mobile_number ?: null,
                            'user_id' => $modUser?->id,
                            'sort_order' => 9999,
                            'source_officer_submission_item_id' => null,
                            'student_id_number' => $this->parseStudentIdFromEmail($modEmail),
                        ]);
                    } else {
                        if ($modUser && (int) $modOfficer->user_id !== (int) $modUser->id) {
                            $modOfficer->user_id = $modUser->id;
                            $modOfficer->save();
                        }
                    }

                    if ($modUser && $modOfficer) {
                        OrgMembership::query()
                            ->where('organization_id', $organization->id)
                            ->where('school_year_id', $encodeSyId)
                            ->where('role', 'moderator')
                            ->where('user_id', $modUser->id)
                            ->whereNull('archived_at')
                            ->update(['officer_entry_id' => $modOfficer->id]);
                    }
                }

                // Treasurer linking: best effort (position == "Treasurer")
                $treasurerItem = $items->first(function ($it) {
                    return mb_strtolower(trim((string) $it->position)) === 'treasurer';
                });

                if ($treasurerItem) {
                    $treasurerOfficer = OfficerEntry::query()
                        ->where('organization_id', $organization->id)
                        ->where('school_year_id', $encodeSyId)
                        ->where('source_officer_submission_item_id', $treasurerItem->id)
                        ->first();

                    if ($treasurerOfficer && (int) $treasurerOfficer->user_id > 0) {
                        OrgMembership::query()
                            ->where('organization_id', $organization->id)
                            ->where('school_year_id', $encodeSyId)
                            ->where('role', 'treasurer')
                            ->where('user_id', $treasurerOfficer->user_id)
                            ->whereNull('archived_at')
                            ->update(['officer_entry_id' => $treasurerOfficer->id]);
                    }
                }
            }, 3); 
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'ALREADY_ACTIVATED') {
                return back()->with('status', 'This organization is already activated for the selected school year.');
            }
            throw $e;
        }

        return back()->with('status', 'Organization activated. Officers and projects were created for the selected school year.');
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
