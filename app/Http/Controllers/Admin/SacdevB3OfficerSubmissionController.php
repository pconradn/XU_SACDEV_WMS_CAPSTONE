<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficerEntry;
use App\Models\OfficerSubmission;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\ReregActionNotification;
use App\Support\AccountProvisioner;
use App\Support\Audit;
use App\Support\InAppNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class SacdevB3OfficerSubmissionController extends Controller
{

    private function presidentForSy(int $orgId, int $targetSyId): ?User
    {
        $membership = OrgMembership::query()
            ->with('user')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'president')
            ->first();

        return $membership?->user;
    }

    public function index(Request $request)
    {
        $targetSyId = (int) ($request->input('target_school_year_id') ?? 0);
        $status = $request->input('status');

        $q = OfficerSubmission::query()
            ->with(['organization', 'targetSchoolYear'])
            ->orderByDesc('submitted_at')
            ->orderByDesc('updated_at');

        if ($targetSyId > 0) {
            $q->where('target_school_year_id', $targetSyId);
        }

        if ($status) {
            $q->where('status', $status);
        } else {
           
            $q->whereIn('status', ['submitted_to_sacdev', 'returned_by_sacdev', 'approved_by_sacdev']);
        }

        $submissions = $q->paginate(15)->withQueryString();
        $schoolYears = SchoolYear::query()->orderByDesc('id')->get();

        return view('admin.forms.b3_officers.index', compact('submissions', 'schoolYears', 'targetSyId', 'status'));
    }

    public function show(OfficerSubmission $submission)
    {
        $submission->load([
            'organization',
            'targetSchoolYear',
            'items'
        ]);

        $syId  = (int) $submission->target_school_year_id;
        $orgId = (int) $submission->organization_id;

        $conflictsByItemId = [];

        foreach ($submission->items as $item)
        {
            if (!$item->student_id_number) continue;

            $conflicts = OfficerEntry::query()
                ->with('organization')
                ->where('student_id_number', $item->student_id_number)
                ->where('school_year_id', $syId)
                ->where('is_major_officer', true)
                ->where('organization_id', '!=', $orgId)
                ->get();

            if ($conflicts->isNotEmpty())
            {
                $conflictsByItemId[$item->id] =
                    $conflicts->map(function ($entry)
                    {
                        return [
                            'organization_id' => $entry->organization_id,
                            'organization_name' => $entry->organization->name ?? 'Unknown Org',
                            'major_officer_role' => $entry->major_officer_role,
                            'position' => $entry->position,
                            'full_name' => $entry->full_name,
                        ];
                    })->values()->toArray();
            }
        }

        return view('admin.forms.b3_officers.show', [
            'submission' => $submission,
            'conflictsByItemId' => $conflictsByItemId,
        ]);
    }

    public function returnToOrg(Request $request, OfficerSubmission $submission)
    {
        $data = $request->validate([
            'sacdev_remarks' => ['required', 'string', 'min:3'],
        ]);

        $orgId = (int) $submission->organization_id;
        $syId  = (int) $submission->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);

        DB::transaction(function () use ($submission, $data, $president, $orgId, $syId) {

            $locked = OfficerSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'submitted_to_sacdev') {
                abort(403, 'Only submitted forms can be returned.');
            }

            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->sacdev_reviewed_by_user_id = Auth::id();
            $locked->sacdev_remarks = $data['sacdev_remarks'];
            $locked->sacdev_reviewed_at = now();
            $locked->save();

            $submissionId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $orgId, $syId, $submissionId) {
                if (!$president) return;

                $dedupeKey = "b3:officer_submission:{$submissionId}:returned_by_sacdev:to:{$president->getKey()}";

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'B3 Officers List returned by SACDEV',
                    'message'      => 'SACDEV returned your Officers List with remarks. Please revise and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b3_officers_list',
                    'status'       => 'returned_by_sacdev',
                    'action_url'   => route('org.rereg.b3.officers-list.edit'),
                    'meta'         => ['submission_id' => $submissionId],
                ]);
            });
        });

        return redirect()
            ->route('admin.officer_submissions.show', $submission->id)
            ->with('success', 'Returned to organization with remarks.');
    }


    public function approve(Request $request, OfficerSubmission $submission)
    {
        $data = $request->validate([
            'sacdev_remarks' => ['nullable', 'string'],
        ]);

        $orgId = (int) $submission->organization_id;
        $syId  = (int) $submission->target_school_year_id;

        DB::transaction(function () use ($submission, $data, $orgId, $syId) {

            $locked = OfficerSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'submitted_to_sacdev') {
                abort(403, 'Only submitted forms can be approved.');
            }


            /*
            |--------------------------------------------------------------------------
            | STEP 0: Update submission status
            |--------------------------------------------------------------------------
            */

            $locked->status = 'approved_by_sacdev';
            $locked->approved_at = now();
            $locked->sacdev_reviewed_by_user_id = auth()->id();
            $locked->sacdev_reviewed_at = now();
            $locked->sacdev_remarks = $data['sacdev_remarks'] ?? null;
            $locked->save();



            /*
            |--------------------------------------------------------------------------
            | STEP 1–10: Process each officer
            |--------------------------------------------------------------------------
            */

            foreach ($locked->items as $item) {

                if ($item->isPropagated()) {
                    continue;
                }

                $studentId = $item->student_id_number;
                $email = $studentId . '@xu.edu.ph';


                /*
                |--------------------------------------------------------------------------
                | STEP 1: Major officer conflict blocking
                |--------------------------------------------------------------------------
                */

                if ($item->is_major_officer) {

                    $conflict = OfficerEntry::query()
                        ->where('student_id_number', $studentId)
                        ->where('school_year_id', $syId)
                        ->where('is_major_officer', true)
                        ->where('organization_id', '!=', $orgId)
                        ->exists();

                    if ($conflict) {
                        abort(
                            422,
                            "{$item->officer_name} is already a major officer in another organization for this school year."
                        );
                    }
                }



                /*
                |--------------------------------------------------------------------------
                | STEP 2: Find or create OfficerEntry
                |--------------------------------------------------------------------------
                */

                $entry = OfficerEntry::query()
                    ->where('organization_id', $orgId)
                    ->where('school_year_id', $syId)
                    ->where('student_id_number', $studentId)
                    ->first();

                if (!$entry) {

                    $entry = new OfficerEntry();

                    $entry->organization_id = $orgId;
                    $entry->school_year_id = $syId;
                    $entry->student_id_number = $studentId;
                }



                /*
                |--------------------------------------------------------------------------
                | STEP 3: Update OfficerEntry snapshot
                |--------------------------------------------------------------------------
                */

                $entry->full_name = $item->officer_name;
                $entry->email = $email;
                $entry->position = $item->position;
                $entry->course_and_year = $item->course_and_year;
                $entry->mobile_number = $item->mobile_number;

                $entry->major_officer_role = $item->major_officer_role;
                $entry->is_major_officer = $item->is_major_officer;

                $entry->prev_first_sem_qpi = $item->first_sem_qpi;
                $entry->prev_second_sem_qpi = $item->second_sem_qpi;
                $entry->prev_intersession_qpi = $item->intersession_qpi;

                $entry->latest_qpi = $item->latest_qpi;

                $entry->sort_order = $item->sort_order;
                $entry->source_officer_submission_item_id = $item->id;

                $entry->save();



                /*
                |--------------------------------------------------------------------------
                | STEP 4: Compute probation status
                |--------------------------------------------------------------------------
                */

                $failingCount = collect([
                    $item->first_sem_qpi,
                    $item->second_sem_qpi,
                    $item->intersession_qpi
                ])
                ->filter()
                ->filter(fn($qpi) => $qpi < 2.0)
                ->count();

                $isUnderProbation = $failingCount >= 2;



                /*
                |--------------------------------------------------------------------------
                | STEP 5: Create OrgMembership for ALL officers
                |--------------------------------------------------------------------------
                */

                $membership = OrgMembership::query()
                    ->where('organization_id', $orgId)
                    ->where('school_year_id', $syId)
                    ->where('officer_entry_id', $entry->id)
                    ->first();

                if (!$membership) {

                    $membership = new OrgMembership();

                    $membership->organization_id = $orgId;
                    $membership->school_year_id = $syId;
                    $membership->officer_entry_id = $entry->id;

                    // user_id intentionally NULL
                    $membership->user_id = null;
                }

                $membership->role = $item->major_officer_role ?? 'officer';
                $membership->is_under_probation = $isUnderProbation;

                $membership->save();



                /*
                |--------------------------------------------------------------------------
                | STEP 6: Provision USER ONLY if Treasurer
                |--------------------------------------------------------------------------
                */

                if ($item->isTreasurer()) {

                    [$user, $tempPassword] =
                        AccountProvisioner::findOrCreateUser(
                            $item->officer_name,
                            $email
                        );

                    // Link OfficerEntry
                    $entry->user_id = $user->id;
                    $entry->save();

                    // Link OrgMembership
                    $membership->user_id = $user->id;
                    $membership->save();


                    AccountProvisioner::ensureBasicOrgAccess(
                        $user->id,
                        $orgId,
                        $syId,
                        $entry->id
                    );


                    Audit::log(
                        'treasurer_provisioned',
                        "Treasurer account provisioned: {$item->officer_name}",
                        [
                            'actor_user_id' => auth()->id(),
                            'organization_id' => $orgId,
                            'school_year_id' => $syId,
                            'meta' => [
                                'student_id_number' => $studentId
                            ]
                        ]
                    );
                }




                $item->propagated_to_memberships = true;
                $item->propagated_at = now();
                $item->save();



        

                Audit::log(
                    'officer_entry_propagated',
                    "Officer propagated: {$item->officer_name}",
                    [
                        'actor_user_id' => auth()->id(),
                        'organization_id' => $orgId,
                        'school_year_id' => $syId,
                        'meta' => [
                            'student_id_number' => $studentId,
                            'role' => $item->major_officer_role,
                            'probation' => $isUnderProbation,
                        ]
                    ]
                );
            }




            $presidentEntry = OfficerEntry::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('major_officer_role', 'president')
                ->first();

            if ($presidentEntry && $presidentEntry->user_id)
            {
                $presidentUser = User::find($presidentEntry->user_id);

                if ($presidentUser)
                {
                    $presidentUser->notify(
                        new ReregActionNotification([
                            'dedupe_key' => 'b2_president_approved_' . $submission->id,

                            'title'   => 'Officer Submission Approved',
                            'message' => 'Your Officer Submission (Form B-3) has been approved by SACDEV.',

                            'org_id'        => $submission->organization_id,
                            'target_sy_id'  => $submission->target_school_year_id,

                            'form'   => 'b3_officer',
                            'status' => 'approved',

                            'action_url' => route('org.rereg.b3.officer.edit'),

                            'send_mail' => true,

                            'meta' => [
                                'submission_id' => $submission->id,
                                'approved_at'   => now()->toDateTimeString(),
                            ],
                        ])
                    );
                }
            }

        });

        return back()->with(
            'success',
            'Officer submission approved. Org memberships created. Treasurer account provisioned. President notified.'
        );
    }


    public function allowEdit(Request $request, OfficerSubmission $submission)
    {
        $data = $request->validate([
            'sacdev_remarks' => ['nullable', 'string', 'max:5000'],
        ]);

        $orgId = (int) $submission->organization_id;
        $syId  = (int) $submission->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);

        DB::transaction(function () use ($request, $submission, $data, $president, $orgId, $syId) {

            $locked = OfficerSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (!$locked->edit_requested) {
                abort(403, 'No edit request is pending for this submission.');
            }

            if (!in_array($locked->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
                abort(403, 'Cannot allow edit for this status.');
            }

            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->sacdev_reviewed_by_user_id = auth()->id();
            $locked->sacdev_reviewed_at = now();

            $base = "Edit request granted. Please update the form then resubmit.";
            $extra = trim((string)($data['sacdev_remarks'] ?? ''));
            $locked->sacdev_remarks = $extra ? ($base . "\n\nSACDEV Note: " . $extra) : $base;

            $locked->edit_requested = false;
            $locked->edit_request_reason = null;
            $locked->edit_requested_by_user_id = null;
            $locked->edit_requested_at = null;

            $locked->approved_at = null;

            $locked->save();

            $submissionId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $orgId, $syId, $submissionId) {
                if (!$president) return;

                $dedupeKey = "b3:officer_submission:{$submissionId}:edit_granted:to:{$president->getKey()}";

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'Edit request granted for B3 Officers List',
                    'message'      => 'SACDEV granted your edit request. Please update the Officers List and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b3_officers_list',
                    'status'       => 'edit_granted',
                    'action_url'   => route('org.rereg.b3.officers-list.edit'),
                    'meta'         => ['submission_id' => $submissionId],
                ]);
            });
        });

        return back()->with('success', 'Edit request granted. The organization can now edit and resubmit.');
    }



}
