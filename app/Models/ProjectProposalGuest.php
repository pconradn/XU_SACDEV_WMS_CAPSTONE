<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProposalGuest extends Model
{
    protected $table = 'project_proposal_guests';

    protected $fillable = [
        'project_document_id',
        'full_name',
        'affiliation',
        'designation',
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }
}