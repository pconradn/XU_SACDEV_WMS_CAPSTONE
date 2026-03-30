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

        'description', 
        

        'source_strategic_plan_project_id', 'status' , 'workflow_status',

        'requires_clearance',
        'clearance_reference',
        'clearance_status',
        'clearance_file_path',

        'clearance_required_at',
        'clearance_uploaded_at',
        'clearance_verified_at',
        'clearance_remarks',

        'implementation_start_date',
        'implementation_end_date',
        'implementation_start_time',
        'implementation_end_time',
        'implementation_venue',
        'implementation_venue_type',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'target_date' => 'date',
        'budget' => 'decimal:2',
        'requires_clearance' => 'boolean',

        'clearance_required_at' => 'datetime',
        'clearance_uploaded_at' => 'datetime',
        'clearance_verified_at' => 'datetime',

        'implementation_start_date' => 'date',
        'implementation_end_date' => 'date',

        'implementation_start_time' => 'datetime:H:i',
        'implementation_end_time' => 'datetime:H:i',
    ];


    public function getImplementationDateDisplayAttribute(): ?string
    {
        if (!$this->implementation_start_date) {
            return null;
        }

        if (
            $this->implementation_end_date &&
            !$this->implementation_start_date->isSameDay($this->implementation_end_date)
        ) {
            return $this->implementation_start_date->format('M d') . ' - ' .
                $this->implementation_end_date->format('M d, Y');
        }

        return $this->implementation_start_date->format('M d, Y');
    }

    public function getImplementationTimeDisplayAttribute(): ?string
    {
        if (!$this->implementation_start_time || !$this->implementation_end_time) {
            return null;
        }

        return date('g:i A', strtotime($this->implementation_start_time))
            . ' - ' .
            date('g:i A', strtotime($this->implementation_end_time));
    }

    public function getImplementationVenueDisplayAttribute(): ?string
    {
        if (!$this->implementation_venue) {
            return null;
        }

        $types = collect(explode(',', (string) $this->implementation_venue_type))
            ->map(fn ($t) => match (trim($t)) {
                'on_campus' => 'On-campus',
                'off_campus' => 'Off-campus',
                default => null,
            })
            ->filter()
            ->values();

        $typeLabel = $types->isNotEmpty() ? $types->implode(', ') : null;

        return $typeLabel
            ? "{$typeLabel} • {$this->implementation_venue}"
            : $this->implementation_venue;
    }

    public function getWorkflowStatusLabelAttribute(): string
    {
        return match ($this->workflow_status) {
            'planning' => 'Planning',
            'drafting' => 'Drafting',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'returned' => 'Returned for Revision',
            'approved' => 'Approved for Implementation',
            'postponed' => 'Postponed',
            'cancelled' => 'Cancelled',
            'post_implementation' => 'Post-Implementation',
            'completed' => 'Completed',
            default => 'Unknown',
        };
    }

    public function getWorkflowStatusBadgeClassAttribute(): string
    {
        return match ($this->workflow_status) {
            'planning' => 'bg-slate-100 text-slate-700 ring-slate-200',
            'drafting' => 'bg-amber-100 text-amber-700 ring-amber-200',
            'submitted', 'under_review' => 'bg-blue-100 text-blue-700 ring-blue-200',
            'returned' => 'bg-orange-100 text-orange-700 ring-orange-200',
            'approved' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            'postponed' => 'bg-yellow-100 text-yellow-700 ring-yellow-200',
            'cancelled' => 'bg-rose-100 text-rose-700 ring-rose-200',
            'post_implementation' => 'bg-indigo-100 text-indigo-700 ring-indigo-200',
            'completed' => 'bg-purple-100 text-purple-700 ring-purple-200',
            default => 'bg-slate-100 text-slate-700 ring-slate-200',
        };
    }



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


    public function projectHead()
    {
        return $this->hasOne(ProjectAssignment::class)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at');
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


    public function submissionPackets(): HasMany
    {
        return $this->hasMany(SubmissionPacket::class);
    }

    

}