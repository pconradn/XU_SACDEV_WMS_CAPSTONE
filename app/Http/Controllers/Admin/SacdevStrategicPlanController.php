<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

            $locked->status = StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV;
            $locked->sacdev_reviewed_by = auth()->id();
            $locked->sacdev_reviewed_at = now();
            $locked->sacdev_remarks = (string) $request->input('remarks');
            $locked->approved_at = null;

            $locked->save();

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

            /*
            |--------------------------------------------------------------------------
            | STEP 1: mark submission approved
            |--------------------------------------------------------------------------
            */

            $locked->status = StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV;
            $locked->approved_at = now();

            $locked->sacdev_reviewed_by = auth()->id();
            $locked->sacdev_reviewed_at = now();

            $locked->save();

            $submissionId = (int) $locked->getKey();


            /*
            |--------------------------------------------------------------------------
            | STEP 2: propagate strategic plan projects → projects table
            |--------------------------------------------------------------------------
            */

            $planProjects = StrategicPlanProject::query()
                ->where('submission_id', $submissionId)
                ->get();

            foreach ($planProjects as $planProject) {

                // find existing propagated project
                $project = Project::query()
                    ->where('source_strategic_plan_project_id', $planProject->id)
                    ->first();

                if (!$project) {

                    $project = new Project();

                    $project->organization_id = $orgId;
                    $project->school_year_id = $syId;

                    $project->source_strategic_plan_project_id = $planProject->id;

                    // initial lifecycle status
                    $project->status = 'planned';
                }

                /*
                |--------------------------------------------------------------------------
                | Only store operational fields
                |--------------------------------------------------------------------------
                */

                $project->title = $planProject->title;

                // do NOT copy budget/category/etc
                // those remain in strategic_plan_projects

                $project->save();


                /*
                |--------------------------------------------------------------------------
                | Audit log
                |--------------------------------------------------------------------------
                */

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


            /*
            |--------------------------------------------------------------------------
            | STEP 3: notify president & moderator
            |--------------------------------------------------------------------------
            */

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


        /*
        |--------------------------------------------------------------------------
        | Handle redirect safely
        |--------------------------------------------------------------------------
        */

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
                    ->route('org.moderator.strategic_plans.show', $locked->getKey()) // or SACDEV show
                    ->with('error', 'Only approved submissions can be reverted.');
            }


            $locked->status = StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV;
            $locked->approved_at = null;

            $locked->sacdev_reviewed_by = auth()->id();
            $locked->sacdev_reviewed_at = now();
            $locked->sacdev_remarks = (string) $request->input('remarks');

            $locked->save();

            $submissionId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $moderator, $orgId, $syId, $submissionId) {

                if ($president) {
                    $dedupeKey = "admin:strategic_plan:{$submissionId}:approval_reverted:to:{$president->getKey()}";
                    InAppNotifier::notifyOnce($president, [
                        'dedupe_key'   => $dedupeKey,
                        'title'        => 'Strategic Plan approval reverted',
                        'message'      => 'SACDEV reverted the approval and returned your Strategic Plan for revision.',
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
                        'title'        => 'Strategic Plan approval reverted',
                        'message'      => 'SACDEV reverted an approved Strategic Plan. The org will revise and resubmit.',
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
            ->with('success', 'Approval reverted. Submission returned to the organization.');
    }

}
