<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProposalPlanOfAction extends Model
{
    protected $table = 'project_proposal_plan_of_actions';

    protected $fillable = [
        'project_document_id',
        'date',
        'time',
        'activity',
        'venue',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }
}