<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationReportObjective extends Model
{
    protected $table = 'documentation_report_objectives';

    protected $fillable = [
        'project_document_id',
        'objective'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }
}