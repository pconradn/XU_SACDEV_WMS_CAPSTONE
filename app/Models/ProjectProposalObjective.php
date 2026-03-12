<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectProposalObjective extends Model
{
    protected $table = 'project_proposal_objectives';

    protected $fillable = [
        'project_document_id',
        'objective',
    ];


    public function document(): BelongsTo
    {
        return $this->belongsTo(
            ProjectDocument::class,
            'project_document_id'
        );
    }

 
    public function project()
    {
        return $this->document->project();
    }
}