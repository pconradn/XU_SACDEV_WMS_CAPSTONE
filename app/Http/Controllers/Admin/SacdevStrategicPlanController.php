<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\StrategicPlanProject;
use App\Models\StrategicPlanSubmission;
use App\Models\User;
use App\Support\Audit;
use App\Support\InAppNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SacdevStrategicPlanController extends Controller
{
    public function index(Request $request)
    {
        $q = StrategicPlanSubmission::query()
            ->with(['organization', 'targetSchoolYear'])
            ->orderByDesc('updated_at');

        // optional quick filters
        if ($status = $request->get('status')) {
            $q->where('status', $status);
        }

        $submissions = $q->paginate(15);

        return view('admin.strategic_plans.index', compact('submissions'));
    }



    private function presidentForSy(int $orgId, int $targetSyId): ?User
    {
        $m = OrgMembership::query()
            ->with('user')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'president')
            ->where('archived_at', null)
            ->first();

        return $m?->user;
    }

    private function moderatorForSy(int $orgId, int $targetSyId): ?User
    {
        $m = OrgMembership::query()
            ->with('user')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'moderator')
            ->where('archived_at', null)
            ->first();

        return $m?->user;
    }

    public function show(StrategicPlanSubmission $submission)
    {
        $submission->load([
            'organization',
            'targetSchoolYear',
            'projects.objectives',
            'projects.beneficiaries',
            'projects.deliverables',
            'projects.partners',
            'fundSources',
            'timelines.user',
        ]);

        return view('admin.strategic_plans.show', compact('submission'));
    }

    public function returnToOrg(Request $request, StrategicPlanSubmission $submission){
        $request->validate([
            'remarks' => ['required', 'string', 'min:5'],
        ]);

        $orgId = (int) $submission->organization_id;
        $syId  = (int) $submission->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);
        $moderator = $this->moderatorForSy($orgId, $syId);

        DB::transaction(function () use ($request, $submission, $president, $moderator, $orgId, $syId) {

            $locked = StrategicPlanSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (! in_array($locked->status, [
                StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV,
                StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV,
            ], true)) {
                return redirect()
                    ->route('org.moderator.strategic_plans.show', $locked->getKey()) // adjust route
                    ->with('error', 'This submission is not in SACDEV review stage.');
            }
            $oldStatus = $locked->status;
            $locked->status = StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV;
            $locked->sacdev_reviewed_by = auth()->id();
            $locked->sacdev_reviewed_at = now();
            $locked->sacdev_remarks = (string) $request->input('remarks');
            $locked->approved_at = null;

            $locked->save();

            $locked->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'returned_by_sacdev',
                'remarks' => $request->input('remarks'), // REQUIRED
                'old_status' => $oldStatus,
                'new_status' => StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV,
            ]);

            $submissionId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $moderator, $orgId, $syId, $submissionId) {

                
                if ($president) {
                    $dedupeKey = "admin:strategic_plan:{$submissionId}:returned_by_sacdev:to:{$president->getKey()}";
                    InAppNotifier::notifyOnce($president, [
                        'dedupe_key'   => $dedupeKey,
                        'title'        => 'Strategic Plan returned by SACDEV',
                        'message'      => 'SACDEV returned your Strategic Plan for revision. Please check the remarks and resubmit.',
                        'org_id'       => $orgId,
                        'target_sy_id' => $syId,
                        'form'         => 'strategic_plan',
                        'status'       => 'returned_by_sacdev',
                        'action_url'   => route('org.rereg.b1.edit', $submissionId),
                        'meta'         => ['submission_id' => $submissionId],
                    ]);
                }

                
                if ($moderator) {
                    $dedupeKey = "admin:strategic_plan:{$submissionId}:returned_by_sacdev:to:{$moderator->getKey()}";
                    InAppNotifier::notifyOnce($moderator, [
                        'dedupe_key'   => $dedupeKey,
                        'title'        => 'Strategic Plan returned by SACDEV',
                        'message'      => 'SACDEV returned a Strategic Plan. The org will revise and resubmit to you after changes.',
                        'org_id'       => $orgId,
                        'target_sy_id' => $syId,
                        'form'         => 'strategic_plan',
                        'status'       => 'returned_by_sacdev',
                        'action_url'   => route('org.moderator.strategic_plans.show', $submissionId),
                        'meta'         => ['submission_id' => $submissionId],
                    ]);
                }
            });
        });

        return redirect()->route('admin.strategic_plans.show', $submission)
            ->with('success', 'Returned to organization for revision.');
    }



    public function approve(Request $request, StrategicPlanSubmission $submission)
    {
        $orgId = (int) $submission->organization_id;
        $syId  = (int) $submission->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);
        $moderator = $this->moderatorForSy($orgId, $syId);

        $result = DB::transaction(function () use ($submission, $president, $moderator, $orgId, $syId) {

            $locked = StrategicPlanSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $allowed = [
                StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV,
            ];

            if (!in_array($locked->status, $allowed, true)) {
                return redirect()
                    ->route('admin.strategic_plans.show', $locked->getKey())
                    ->with('error', 'This submission is not ready for approval.');
            }


            $oldStatus = $locked->status;
            $locked->status = StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV;
            $locked->approved_at = now();

            $locked->sacdev_reviewed_by = auth()->id();
            $locked->sacdev_reviewed_at = now();

            $locked->save();

            $submissionId = (int) $locked->getKey();
            
            $organization = Organization::query()
                ->lockForUpdate()
                ->findOrFail($orgId);

            $organization->name = $locked->org_name;
            $organization->acronym = $locked->org_acronym;

            $organization->mission = $locked->mission;
            $organization->vision = $locked->vision;

            $organization->logo_path = $locked->logo_path;
            $organization->logo_original_name = $locked->logo_original_name;
            $organization->logo_mime = $locked->logo_mime;
            $organization->logo_size_bytes = $locked->logo_size_bytes;

            $organization->last_b1_submission_id = $submissionId;

            $organization->save();

            $locked->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'approved_by_sacdev',
                'remarks' => null, 
                'old_status' => $oldStatus,
                'new_status' => StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV,
            ]);

            Audit::log(
                'organization_profile_updated_from_strategic_plan',
                "Organization profile updated from approved Strategic Plan",
                [
                    'actor_user_id' => auth()->id(),
                    'organization_id' => $orgId,
                    'school_year_id' => $syId,
                    'meta' => [
                        'submission_id' => $submissionId,
                    ]
                ]
            );


            $planProjects = StrategicPlanProject::query()
                ->where('submission_id', $submissionId)
                ->get();

            foreach ($planProjects as $planProject) {

               
                $project = Project::query()
                    ->where('source_strategic_plan_project_id', $planProject->id)
                    ->first();

                if (!$project) {

                    $project = new Project();

                    $project->organization_id = $orgId;
                    $project->school_year_id = $syId;

                    $project->source_strategic_plan_project_id = $planProject->id;

                  
                    $project->status = 'planned';
                }

 

                $project->title = $planProject->title;


                $project->save();


                Audit::log(
                    'strategic_plan_project_created',
                    "Project created from strategic plan: {$project->title}",
                    [
                        'actor_user_id' => auth()->id(),
                        'organization_id' => $orgId,
                        'school_year_id' => $syId,
                        'meta' => [
                            'project_id' => $project->id,
                            'strategic_plan_project_id' => $planProject->id,
                            'submission_id' => $submissionId,
                        ]
                    ]
                );
            }


            DB::afterCommit(function () use ($president, $moderator, $orgId, $syId, $submissionId) {

                if ($president) {

                    $dedupeKey =
                        "admin:strategic_plan:{$submissionId}:approved_by_sacdev:to:{$president->getKey()}";

                    InAppNotifier::notifyOnce($president, [
                        'dedupe_key'   => $dedupeKey,
                        'title'        => 'Strategic Plan Approved by SACDEV',
                        'message'      => 'Your Strategic Plan has been approved by SACDEV.',
                        'org_id'       => $orgId,
                        'target_sy_id' => $syId,
                        'form'         => 'strategic_plan',
                        'status'       => 'approved_by_sacdev',
                        'action_url'   => route('org.rereg.b1.edit'),
                        'meta'         => ['submission_id' => $submissionId],
                        'send_mail'    => true,
                    ]);
                }

                if ($moderator) {

                    $dedupeKey =
                        "admin:strategic_plan:{$submissionId}:approved_by_sacdev:to:{$moderator->getKey()}";

                    InAppNotifier::notifyOnce($moderator, [
                        'dedupe_key'   => $dedupeKey,
                        'title'        => 'Strategic Plan Approved by SACDEV',
                        'message'      => 'A Strategic Plan you forwarded has been approved by SACDEV.',
                        'org_id'       => $orgId,
                        'target_sy_id' => $syId,
                        'form'         => 'strategic_plan',
                        'status'       => 'approved_by_sacdev',
                        'action_url'   => route('org.moderator.strategic_plans.show', $submissionId),
                        'meta'         => ['submission_id' => $submissionId],
                        'send_mail'    => true,
                    ]);
                }
            });

            return true;
        });



        if ($result instanceof RedirectResponse) {
            return $result;
        }

        return redirect()
            ->route('admin.strategic_plans.show', $submission->getKey())
            ->with('success', 'Approved by SACDEV and projects created successfully.');
    }



    public function revertApproval(Request $request, StrategicPlanSubmission $submission)
    {
        $request->validate([
            'remarks' => ['required', 'string', 'min:8'],
        ]);

        $orgId = (int) $submission->organization_id;
        $syId  = (int) $submission->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);
        $moderator = $this->moderatorForSy($orgId, $syId);

        DB::transaction(function () use ($request, $submission, $president, $moderator, $orgId, $syId) {

            $locked = StrategicPlanSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV) {
                return redirect()
                    ->route('org.moderator.strategic_plans.show', $locked->getKey())
                    ->with('error', 'Only approved submissions can be reverted.');
            }

            // 🔥 CAPTURE OLD STATUS
            $oldStatus = $locked->status;

            // 🔥 NEW BEHAVIOR: revert to SACDEV review stage
            $locked->status = StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV;

            $locked->approved_at = null;

            $locked->sacdev_reviewed_by = auth()->id();
            $locked->sacdev_reviewed_at = now();
            $locked->sacdev_remarks = (string) $request->input('remarks');

            $locked->save();

            // 🔥 TIMELINE ENTRY
            $locked->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'approval_reverted',
                'remarks' => $request->input('remarks'),
                'old_status' => $oldStatus,
                'new_status' => StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV,
            ]);

            $submissionId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $moderator, $orgId, $syId, $submissionId) {

                if ($president) {
                    $dedupeKey = "admin:strategic_plan:{$submissionId}:approval_reverted:to:{$president->getKey()}";

                    InAppNotifier::notifyOnce($president, [
                        'dedupe_key'   => $dedupeKey,
                        'title'        => 'Strategic Plan Approval Reverted',
                        'message'      => 'SACDEV reverted the approval. Your submission is now back under SACDEV review.',
                        'org_id'       => $orgId,
                        'target_sy_id' => $syId,
                        'form'         => 'strategic_plan',
                        'status'       => 'approval_reverted',
                        'action_url'   => route('org.rereg.b1.edit', $submissionId),
                        'meta'         => ['submission_id' => $submissionId],
                    ]);
                }

                if ($moderator) {
                    $dedupeKey = "admin:strategic_plan:{$submissionId}:approval_reverted:to:{$moderator->getKey()}";

                    InAppNotifier::notifyOnce($moderator, [
                        'dedupe_key'   => $dedupeKey,
                        'title'        => 'Strategic Plan Approval Reverted',
                        'message'      => 'SACDEV reverted an approved Strategic Plan. It is now back under SACDEV review.',
                        'org_id'       => $orgId,
                        'target_sy_id' => $syId,
                        'form'         => 'strategic_plan',
                        'status'       => 'approval_reverted',
                        'action_url'   => route('org.moderator.strategic_plans.show', $submissionId),
                        'meta'         => ['submission_id' => $submissionId],
                    ]);
                }
            });
        });

        return redirect()->route('admin.strategic_plans.show', $submission)
            ->with('success', 'Approval reverted. Submission is back in SACDEV review stage.');
    }

}
