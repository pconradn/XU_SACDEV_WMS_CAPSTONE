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
        'requires_clearance',
        'clearance_reference',
        'clearance_status',
        'clearance_file_path',
        'clearance_required_at',
        'clearance_uploaded_at',
        'clearance_verified_at',
        'clearance_remarks',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'target_date' => 'date',
        'budget' => 'decimal:2',
        'requires_clearance' => 'boolean',

        'clearance_required_at' => 'datetime',
        'clearance_uploaded_at' => 'datetime',
        'clearance_verified_at' => 'datetime',


    ];


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



    
    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }

    
    public function proposalDocument()
    {
        return $this->hasOne(ProjectDocument::class)
            ->whereHas('formType', function ($q) {
                $q->where('code', 'project_proposal');
            });
    }

    public function clearanceRequired()
    {
        return $this->requires_clearance === true;
    }

    public function clearanceUploaded()
    {
        return $this->clearance_status === 'uploaded';
    }

    public function clearanceVerified()
    {
        return $this->clearance_status === 'verified';
    }   


    public static function generateClearanceReference()
    {
        $year = now()->year;

        $last = self::whereYear('created_at', $year)
            ->whereNotNull('clearance_reference')
            ->orderByDesc('id')
            ->first();

        $number = 1;

        if ($last && preg_match('/CL-\d{4}-(\d+)/', $last->clearance_reference, $matches)) {
            $number = intval($matches[1]) + 1;
        }

        return sprintf('CL-%s-%05d', $year, $number);
    }



}