<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'organization_id',
        'school_year_id',
        'title',
        'category',
        'target_date',
        'implementing_body',
        'budget',
        'source_strategic_plan_project_id',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'target_date' => 'date',
        'budget' => 'decimal:2',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(ProjectAssignment::class);
    }

    public function getProposalStatusAttribute()
    {
        return $this->proposalDocument?->status ?? 'not_created';
    }

    public function strategicPlanProject(): BelongsTo
    {
        return $this->belongsTo(
            StrategicPlanProject::class,
            'source_strategic_plan_project_id'
        );
    }

    // all project documents
    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }

    // for project proposal document
    public function proposalDocument()
    {
        return $this->hasOne(ProjectDocument::class)
            ->whereHas('formType', function ($q) {
                $q->where('code', 'project_proposal');
            });
    }
}