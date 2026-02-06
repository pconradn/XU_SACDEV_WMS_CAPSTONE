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
        // Optional filter by target SY (like org side). If you already store active_sy_id in session,
        // use that as default. Otherwise allow showing all.
        $targetSyId = (int) ($request->input('target_school_year_id') ?? session('encode_sy_id') ?? 0);

        $q = PresidentRegistration::query()
            ->with(['organization', 'targetSchoolYear'])
            ->orderByDesc('submitted_at')
            ->orderByDesc('updated_at');

        if ($targetSyId > 0) {
            $q->where('target_school_year_id', $targetSyId);
        }

        // Common list default: show anything SACDEV needs to see
        // submitted / returned / approved
        $status = $request->input('status');
        if ($status) {
            $q->where('status', $status);
        } else {
            $q->whereIn('status', ['submitted_to_sacdev', 'returned_by_sacdev', 'approved_by_sacdev']);
        }

        $registrations = $q->paginate(15)->withQueryString();

        // If you have SchoolYear model, pass it here. Otherwise remove.
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

        // SACDEV view is always read-only for the form fields.
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

            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->sacdev_reviewed_by_user_id = Auth::id();
            $locked->sacdev_remarks = $data['sacdev_remarks'];
            $locked->sacdev_reviewed_at = now();
            $locked->save();

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

            $locked->status = 'approved_by_sacdev';
            $locked->approved_at = now();
            $locked->sacdev_reviewed_by_user_id = Auth::id();
            $locked->sacdev_remarks = $data['sacdev_remarks'] ?? null;
            $locked->sacdev_reviewed_at = now();
            $locked->save();

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
                    'action_url'   => route('org.rereg.b2.president.index'),
                    'meta'         => ['registration_id' => $regId],
                ]);
            });
        });

        return redirect()
            ->route('admin.b2.president.show', $registration->id)
            ->with('success', 'Approved successfully.');
    }

}
