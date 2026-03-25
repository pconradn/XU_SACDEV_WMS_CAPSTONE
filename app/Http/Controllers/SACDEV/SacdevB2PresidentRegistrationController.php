<?php

namespace App\Http\Controllers\SACDEV;

use App\Http\Controllers\Controller;
use App\Models\PresidentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrgMembership;
use App\Models\User;
use App\Support\InAppNotifier;
use Illuminate\Support\Facades\DB;

class SacdevB2PresidentRegistrationController extends Controller
{
    public function index(Request $request)
    {
       
        $targetSyId = (int) ($request->input('target_school_year_id') ?? session('encode_sy_id') ?? 0);

        $q = PresidentRegistration::query()
            ->with(['organization', 'targetSchoolYear'])
            ->orderByDesc('submitted_at')
            ->orderByDesc('updated_at');

        if ($targetSyId > 0) {
            $q->where('target_school_year_id', $targetSyId);
        }

      
        $status = $request->input('status');
        if ($status) {
            $q->where('status', $status);
        } else {
            $q->whereIn('status', ['submitted_to_sacdev', 'returned_by_sacdev', 'approved_by_sacdev']);
        }

        $registrations = $q->paginate(15)->withQueryString();

        
        $schoolYears = \App\Models\SchoolYear::query()->orderByDesc('id')->get();

        return view('admin.forms.b2_president.index', compact('registrations', 'schoolYears', 'targetSyId', 'status'));
    }


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

    public function show(PresidentRegistration $registration)
    {
        $registration->load(['organization', 'leaderships', 'trainings', 'awards', 'targetSchoolYear']);

      
        $isLocked = true;

        return view('admin.forms.b2_president.show', compact('registration', 'isLocked'));
    }

    public function returnToOrg(Request $request, PresidentRegistration $registration)
    {
        $data = $request->validate([
            'sacdev_remarks' => ['required', 'string', 'min:3'],
        ]);

        $orgId = (int) $registration->organization_id;
        $syId  = (int) $registration->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);

        DB::transaction(function () use ($registration, $data, $president, $orgId, $syId) {

            $locked = PresidentRegistration::query()
                ->whereKey($registration->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'submitted_to_sacdev') {
                abort(403, 'Only submitted forms can be returned.');
            }
            $oldStatus = $locked->getOriginal('status');
            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->sacdev_reviewed_by_user_id = Auth::id();
            $locked->sacdev_remarks = $data['sacdev_remarks'];
            $locked->sacdev_reviewed_at = now();
            $locked->save();

            $locked->timelines()->create([
                'user_id' => Auth::id(),
                'action' => 'returned_by_sacdev',
                'remarks' => $data['sacdev_remarks'],
                'old_status' => $oldStatus,
                'new_status' => 'returned_by_sacdev',
            ]);

            $regId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $orgId, $syId, $regId) {
                if (!$president) return;

                $dedupeKey = "b2:president_registration:{$regId}:returned_by_sacdev:to:{$president->getKey()}";

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'B2 President Registration returned by SACDEV',
                    'message'      => 'SACDEV returned your President Registration with remarks. Please revise and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b2_president_registration',
                    'status'       => 'returned_by_sacdev',
                    'action_url'   => route('org.rereg.b2.president.edit'),
                    'meta'         => ['registration_id' => $regId],
                ]);
            });
        });

        return redirect()
            ->route('admin.b2.president.show', $registration->id)
            ->with('success', 'Returned to organization with remarks.');
    }


    public function approve(Request $request, PresidentRegistration $registration)
    {
        $data = $request->validate([
            'sacdev_remarks' => ['nullable', 'string'],
        ]);

        $orgId = (int) $registration->organization_id;
        $syId  = (int) $registration->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);

        DB::transaction(function () use ($registration, $data, $president, $orgId, $syId) {

            $locked = PresidentRegistration::query()
                ->whereKey($registration->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'submitted_to_sacdev') {
                abort(403, 'Only submitted forms can be approved.');
            }
            $oldStatus = $locked->getOriginal('status');

            $locked->status = 'approved_by_sacdev';
            $locked->approved_at = now();
            $locked->sacdev_reviewed_by_user_id = Auth::id();
            $locked->sacdev_remarks = $data['sacdev_remarks'] ?? null;
            $locked->sacdev_reviewed_at = now();
            $locked->save();

            $locked->timelines()->create([
                'user_id' => Auth::id(),
                'action' => 'approved_by_sacdev',
                'remarks' => $data['sacdev_remarks'] ?? null,
                'old_status' => $oldStatus,
                'new_status' => 'approved_by_sacdev',
            ]);

            $regId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $orgId, $syId, $regId) {
                if (!$president) return;

                $dedupeKey = "b2:president_registration:{$regId}:approved_by_sacdev:to:{$president->getKey()}";

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'B2 President Registration approved by SACDEV',
                    'message'      => 'Your President Registration has been approved by SACDEV.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b2_president_registration',
                    'status'       => 'approved_by_sacdev',
                    'action_url'   => route('org.rereg.b2.president.edit'),
                    'meta'         => ['registration_id' => $regId],
                ]);
            });
        });

        return redirect()
            ->route('admin.b2.president.show', $registration->id)
            ->with('success', 'Approved successfully.');
    }

    public function allowEdit(Request $request, PresidentRegistration $registration)
    {
        $orgId = (int) $registration->organization_id;
        $syId  = (int) $registration->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);

        DB::transaction(function () use ($request, $registration, $president, $orgId, $syId) {

            $locked = PresidentRegistration::query()
                ->whereKey($registration->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (!$locked->edit_requested) {
                abort(403, 'No edit request is pending for this registration.');
            }

            if (!in_array($locked->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
                abort(403, 'Allow edit is only valid when the form is submitted or approved.');
            }

            $data = $request->validate([
                'sacdev_remarks' => ['nullable', 'string', 'max:5000'],
            ]);

            $oldStatus = $locked->getOriginal('status');

            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->approved_at = null;

            $msg = trim((string)($data['sacdev_remarks'] ?? ''));
            if ($msg === '') {
                $msg = 'Edit request granted. Please update the form and resubmit.';
            }

            $locked->sacdev_reviewed_by_user_id = auth()->id();
            $locked->sacdev_remarks = $msg;
            $locked->sacdev_reviewed_at = now();

            // clear edit request flags
            $locked->edit_requested = false;
            $locked->edit_requested_at = null;
            $locked->edit_requested_by_user_id = null;
            $locked->edit_request_message = null;

            $locked->save();

            // timeline
            $locked->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'edit_granted',
                'remarks' => $msg,
                'old_status' => $oldStatus,
                'new_status' => 'returned_by_sacdev',
            ]);

            $regId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $orgId, $syId, $regId) {
                if (!$president) return;

                $dedupeKey = "b2:president_registration:{$regId}:edit_granted:to:{$president->getKey()}";

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'Edit request granted for B2 President Registration',
                    'message'      => 'SACDEV granted your edit request. Please update and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b2_president_registration',
                    'status'       => 'edit_granted',
                    'action_url'   => route('org.rereg.b2.president.edit'),
                    'meta'         => ['registration_id' => $regId],
                ]);
            });
        });

        return back()->with('success', 'Edit access granted. Registration returned for revision.');
    }   


    public function revertApproval(Request $request, PresidentRegistration $registration)
    {
        $data = $request->validate([
            'sacdev_remarks' => ['required', 'string', 'min:3', 'max:5000'],
        ]);

        $orgId = (int) $registration->organization_id;
        $syId  = (int) $registration->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);

        DB::transaction(function () use ($registration, $data, $president, $orgId, $syId) {

            $locked = PresidentRegistration::query()
                ->whereKey($registration->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'approved_by_sacdev') {
                abort(403, 'Only approved registrations can be reverted.');
            }

            $oldStatus = $locked->getOriginal('status');

            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->approved_at = null;

            $locked->sacdev_reviewed_by_user_id = auth()->id();
            $locked->sacdev_remarks = $data['sacdev_remarks'];
            $locked->sacdev_reviewed_at = now();

            $locked->edit_requested = false;
            $locked->edit_requested_at = null;
            $locked->edit_requested_by_user_id = null;
            $locked->edit_request_message = null;

            $locked->save();

            // timeline
            $locked->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'approval_reverted',
                'remarks' => $data['sacdev_remarks'],
                'old_status' => $oldStatus,
                'new_status' => 'returned_by_sacdev',
            ]);

            $regId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $orgId, $syId, $regId) {
                if (!$president) return;

                $dedupeKey = "b2:president_registration:{$regId}:approval_reverted:to:{$president->getKey()}";

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'B2 President Registration approval reverted',
                    'message'      => 'SACDEV reverted the approval and returned your registration for revision.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b2_president_registration',
                    'status'       => 'approval_reverted',
                    'action_url'   => route('org.rereg.b2.president.edit'),
                    'meta'         => ['registration_id' => $regId],
                ]);
            });
        });

        return back()->with('success', 'Approval reverted and returned for revision.');
    }

}
