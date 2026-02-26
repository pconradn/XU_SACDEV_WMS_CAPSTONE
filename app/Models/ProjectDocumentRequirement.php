<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocumentRequirement extends Model
{
    protected $fillable = [
        'project_id',
        'form_type_id',
        'is_required',
        'set_by_user_id',
    ];

    public function formType()
    {
        return $this->belongsTo(FormType::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}