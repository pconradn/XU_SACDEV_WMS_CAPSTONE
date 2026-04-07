<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProposalData extends Model
{
    protected $table = 'project_proposal_data';

    protected $fillable = [
        'project_document_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'venue_type',
        'venue_name',
        'on_campus_venue',
        'off_campus_venue',

        'engagement_type',
        'main_organizer',
        'project_nature',
        'project_nature_other',
        'sdg',
        'area_focus',
        'description',
        'org_link',
        'org_cluster',
        'total_budget',
        'audience_type',
        'audience_details',
        'xu_subtypes',
        'expected_xu_participants',
        'expected_non_xu_participants',
        'has_guest_speakers',
        'sacdev_remarks',
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }

    public function objectives()
    {
        return $this->hasMany(ProjectProposalObjective::class, 'project_document_id', 'project_document_id');
    }

    public function indicators()
    {
        return $this->hasMany(ProjectProposalSuccessIndicator::class, 'project_document_id', 'project_document_id');
    }

    public function partners()
    {
        return $this->hasMany(ProjectProposalPartner::class, 'project_document_id', 'project_document_id');
    }

    public function roles()
    {
        return $this->hasMany(ProjectProposalRole::class, 'project_document_id', 'project_document_id');
    }

    public function guests()
    {
        return $this->hasMany(ProjectProposalGuest::class, 'project_document_id', 'project_document_id');
    }

    public function planOfActions()
    {
        return $this->hasMany(ProjectProposalPlanOfAction::class, 'project_document_id', 'project_document_id');
    }

    public function projectDocument()
    {
        return $this->belongsTo(ProjectDocument::class);
    }

    public function fundSources()
    {
        return $this->hasMany(ProjectProposalFundSource::class);
    }

    public function budgetDocument()
    {
        return $this->hasOne(ProjectDocument::class, 'project_id', 'project_id')
            ->whereHas('formType', function ($q) {
                $q->where('code', 'BUDGET_PROPOSAL');
            });
    }

    public function getProjectIdAttribute()
    {
        return $this->document?->project_id;
    }
}