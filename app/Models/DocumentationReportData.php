<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationReportData extends Model
{
    protected $table = 'documentation_report_data';

    protected $fillable = [
        'project_document_id',
        'objectives_met',
        'contributing_factors',
        'expected_participants',
        'actual_participants',
        'implementation_rating',
        'pre_implementation_stage',
        'implementation_stage',
        'post_implementation_stage',
        'recommendations',
        'proposed_budget',
        'actual_budget',
        'balance',
        'photo_document_path',
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

    public function objectives()
    {
        return $this->hasMany(
            DocumentationReportObjective::class,
            'project_document_id',
            'project_document_id'
        );
    }

    public function indicators()
    {
        return $this->hasMany(
            DocumentationReportIndicator::class,
            'project_document_id',
            'project_document_id'
        );
    }

    public function partners()
    {
        return $this->hasMany(
            DocumentationReportPartner::class,
            'project_document_id',
            'project_document_id'
        );
    }
}