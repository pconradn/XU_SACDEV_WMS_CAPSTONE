<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationReportIndicator extends Model
{
    protected $table = 'documentation_report_indicators';

    protected $fillable = [
        'project_document_id',
        'indicator'
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