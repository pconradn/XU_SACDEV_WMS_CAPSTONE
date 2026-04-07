<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//SCHOOL YEAR CRUD

class SchoolYearController extends Controller
{
    public function index()
    {
        $schoolYears = SchoolYear::query()
            ->orderBy('start_date')
            ->get();

        return view('admin.school_years.index', compact('schoolYears'));
    }

    public function create()
    {
        return view('admin.school_years.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:school_years,name'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        
        $data['is_active'] = false;

        SchoolYear::create($data);

        return redirect()
            ->route('admin.school-years.index')
            ->with('status', 'School year created.');
    }

    public function edit(SchoolYear $schoolYear)
    {
        return view('admin.school_years.edit', compact('schoolYear'));
    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:school_years,name,' . $schoolYear->id],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $schoolYear->update($data);

        return redirect()
            ->route('admin.school-years.index')
            ->with('status', 'School year updated.');
    }

    public function destroy(SchoolYear $schoolYear)
    {
        if ($schoolYear->is_active) {
            return back()->with('status', 'You cannot delete the active school year.');
        }

        $schoolYear->delete();

        return redirect()
            ->route('admin.school-years.index')
            ->with('status', 'School year deleted.');
    }


    public function activate(SchoolYear $schoolYear)
    {
        DB::transaction(function () use ($schoolYear) {
            SchoolYear::query()->where('is_active', true)->update(['is_active' => false]);
            $schoolYear->update(['is_active' => true]);
        });

        return redirect()
            ->route('admin.school-years.index')
            ->with('status', "Activated school year: {$schoolYear->name}");
    }
}
