<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberList;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SacdevB4MemberListController extends Controller
{
    public function index(Request $request)
    {
        $targetSyId = (int) ($request->input('target_school_year_id') ?? 0);
        $qText = trim((string) $request->input('q'));

        $q = MemberList::query()
            ->with(['organization', 'targetSchoolYear'])
            ->orderByDesc('updated_at');

        if ($targetSyId > 0) {
            $q->where('target_school_year_id', $targetSyId);
        }

        if ($qText !== '') {
            $q->whereHas('organization', function ($oq) use ($qText) {
                $oq->where('name', 'like', "%{$qText}%")
                   ->orWhere('acronym', 'like', "%{$qText}%");
            });
        }

        $lists = $q->paginate(15)->withQueryString();
        $schoolYears = SchoolYear::query()->orderByDesc('id')->get();

        return view('admin.forms.b4_members.index', compact('lists', 'schoolYears', 'targetSyId', 'qText'));
    }

    public function show(MemberList $list)
    {
        $list->load(['organization', 'targetSchoolYear', 'items']);

        return view('admin.forms.b4_members.show', compact('list'));
    }
}
