<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    protected $fillable = [
        'project_id',
        'form_type_id',
        'created_by_user_id',
        'status',
        'submitted_at',
        'reviewed_at',
        'reviewed_by_user_id',
        'remarks',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function formType()
    {
        return $this->belongsTo(FormType::class);
    }

    public function signatures()
    {
        return $this->hasMany(ProjectDocumentSignature::class);
    }

    public function proposalData()
    {
        return $this->hasOne(ProjectProposalData::class, 'project_document_id');
    }
}