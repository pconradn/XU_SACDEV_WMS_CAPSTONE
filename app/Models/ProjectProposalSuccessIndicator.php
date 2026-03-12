<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProposalSuccessIndicator extends Model
{
    protected $table = 'project_proposal_success_indicators';

    protected $fillable = [
        'project_document_id',
        'indicator',
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }
}