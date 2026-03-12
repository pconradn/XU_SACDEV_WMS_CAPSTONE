<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitationApplicationData extends Model
{

    protected $fillable = [

        'project_document_id',

        'activity_name',
        'purpose',

        'duration_from',
        'duration_to',

        'target_amount',
        'desired_letter_count',

        'target_student_orgs',
        'target_xu_officers',
        'target_private_individuals',
        'target_alumni',
        'target_private_companies',

        'target_others',
        'target_others_specify',

        'letter_draft_link',

        'approved_letter_count',
        'control_numbers_series',

    ];



    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }

}