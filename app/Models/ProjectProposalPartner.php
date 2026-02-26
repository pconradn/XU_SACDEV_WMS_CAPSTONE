<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProposalPartner extends Model
{
    protected $table = 'project_proposal_partners';

    protected $fillable = [
        'project_document_id',
        'name',
        'type',
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }
}