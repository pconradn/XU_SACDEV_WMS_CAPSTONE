<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProposalRole extends Model
{
    protected $table = 'project_proposal_roles';

    protected $fillable = [
        'project_document_id',
        'role_name',
        'description',
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }
}