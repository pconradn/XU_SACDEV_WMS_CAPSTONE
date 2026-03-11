<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationReportPartner extends Model
{
    protected $table = 'documentation_report_partners';

    protected $fillable = [
        'project_document_id',
        'name',
        'type'
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