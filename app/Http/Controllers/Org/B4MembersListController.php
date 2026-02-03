<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\MemberList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class B4MembersListController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'targetSyId' => (int) $request->session()->get('encode_sy_id'), // match your existing key
        ];
    }

    public function index(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $schoolYears = \App\Models\SchoolYear::query()->orderByDesc('id')->get();

        $list = null;
        if ($orgId && $targetSyId) {
            $list = MemberList::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->first();
        }

        return view('org.forms.b4_members.index', compact('schoolYears', 'targetSyId', 'list'));
    }

    public function setTargetSy(Request $request)
    {
        $data = $request->validate([
            'target_school_year_id' => ['required', 'integer'],
        ]);

        $request->session()->put('encode_sy_id', (int) $data['target_school_year_id']);

        return redirect()->route('org.b4.members-list.edit');
    }

    public function edit(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        if (!$orgId || !$targetSyId) {
            return redirect()->route('org.b4.members-list.index')
                ->with('error', 'Please select a Target School Year first.');
        }

        $list = MemberList::query()
            ->with('items')
            ->firstOrCreate(
                ['organization_id' => $orgId, 'target_school_year_id' => $targetSyId],
                ['encoded_by_user_id' => auth()->id(), 'certified' => false]
            );

        return view('org.forms.b4_members.edit', compact('list', 'targetSyId'));
    }

    private function rowEmpty(array $row): bool
    {
        $values = [
            $row['full_name'] ?? null,
            $row['student_id_number'] ?? null,
            $row['course_and_year'] ?? null,
            $row['latest_qpi'] ?? null,
            $row['mobile_number'] ?? null,
        ];

        foreach ($values as $v) {
            if ($v !== null && trim((string)$v) !== '') return false;
        }
        return true;
    }

    public function save(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $list = MemberList::query()
            ->with('items')
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $targetSyId)
            ->firstOrFail();

        // Always editable: just validate formats
        $validated = $request->validate([
            'certified' => ['nullable', 'boolean'],

            'items' => ['nullable', 'array'],
            'items.*.full_name' => ['nullable', 'string', 'max:255'],
            'items.*.student_id_number' => ['nullable', 'string', 'max:50'],
            'items.*.course_and_year' => ['nullable', 'string', 'max:255'],
            'items.*.latest_qpi' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'items.*.mobile_number' => ['nullable', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($request, $list) {
            $list->encoded_by_user_id = $list->encoded_by_user_id ?? auth()->id();
            $list->certified = (bool) $request->boolean('certified');
            $list->save();

            // sync rows (delete + recreate)
            $list->items()->delete();

            foreach (($request->input('items') ?? []) as $i => $row) {
                if (!is_array($row) || $this->rowEmpty($row)) continue;

                $list->items()->create([
                    'full_name' => $row['full_name'] ?? '',
                    'student_id_number' => $row['student_id_number'] ?? '',
                    'course_and_year' => $row['course_and_year'] ?? '',
                    'latest_qpi' => $row['latest_qpi'] ?? null,
                    'mobile_number' => $row['mobile_number'] ?? '',
                    'sort_order' => $i + 1,
                ]);
            }
        });

        return back()->with('success', 'Members list saved.');
    }
}