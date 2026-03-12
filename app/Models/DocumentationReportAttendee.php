<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationReportAttendee extends Model
{
    protected $table = 'documentation_report_attendees';

    protected $fillable = [
        'project_document_id',
        'name',
        'affiliation',
        'designation',
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }
}