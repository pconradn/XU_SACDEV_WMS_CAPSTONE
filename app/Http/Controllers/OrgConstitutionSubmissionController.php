<?php

namespace App\Http\Controllers;

use App\Models\OrgConstitutionSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrgConstitutionSubmissionController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:25600'
            ],
        ]);

        $user = auth()->user();

        $orgId = (int) session('active_org_id');
        $syId  = (int) session('encode_sy_id');

        if (!$orgId || !$syId) {
            abort(403, 'Organization or School Year context missing.');
        }

        $file = $request->file('file');



        $existing = OrgConstitutionSubmission::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->first();

 

        if ($existing && $existing->file_path) {
            Storage::disk('public')->delete($existing->file_path);
        }

     

        $path = $file->store(
            "org_constitutions/{$orgId}/{$syId}",
            'public'
        );


        OrgConstitutionSubmission::updateOrCreate(
            [
                'organization_id' => $orgId,
                'school_year_id' => $syId,
            ],
            [
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'submitted_by_user_id' => $user->id,
                'submitted_at' => now(),

             
                'status' => 'submitted',
                'approved_by_user_id' => null,
                'approved_at' => null,
            ]
        );

        return back()->with('success', 'Organization Constitution uploaded successfully.');
    }

    public function download(OrgConstitutionSubmission $submission)
    {
        return Storage::disk('public')->download(
            $submission->file_path,
            $submission->original_filename
        );
    }

    public function approve(Request $request, OrgConstitutionSubmission $submission)
    {
        $submission->status = 'approved_by_sacdev';

        $submission->approved_by_user_id = auth()->id();

        $submission->approved_at = now();

        $submission->save();

        return back()->with('success', 'Constitution approved.');
    }
}