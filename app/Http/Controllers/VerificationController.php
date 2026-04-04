<?php

namespace App\Http\Controllers;

use App\Models\ProjectDocument;

class VerificationController extends Controller
{
    public function show($token)
    {
        $document = ProjectDocument::with([
            'project.organization',
            'formType'
        ])
        ->where('verification_token', $token)
        ->firstOrFail();

        return view('verification.show', [
            'document' => $document
        ]);
    }
}