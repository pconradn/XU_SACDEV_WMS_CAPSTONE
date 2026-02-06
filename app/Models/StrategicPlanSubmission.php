<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrategicPlanSubmission extends Model
{
    // Status constant
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED_TO_MODERATOR = 'submitted_to_moderator';
    public const STATUS_RETURNED_BY_MODERATOR = 'returned_by_moderator';
    public const STATUS_FORWARDED_TO_SACDEV = 'forwarded_to_sacdev';
    public const STATUS_RETURNED_BY_SACDEV = 'returned_by_sacdev';
    public const STATUS_APPROVED_BY_SACDEV = 'approved_by_sacdev';
    public const STATUS_SUBMITTED_TO_SACDEV = 'submitted_to_sacdev';


    protected $fillable = [
        'organization_id',
        'target_school_year_id',
        'submitted_by_user_id',

        'status',

        'org_acronym',
        'org_name',
        'mission',
        'vision',

        'logo_path',
        'logo_original_name',
        'logo_mime',
        'logo_size_bytes',

        'total_org_dev',
        'total_student_services',
        'total_community_involvement',
        'total_overall',

        'submitted_to_moderator_at',
        'forwarded_to_sacdev_at',
        'approved_at',

        'moderator_reviewed_by',
        'moderator_reviewed_at',
        'moderator_remarks',

        'sacdev_reviewed_by',
        'sacdev_reviewed_at',
        'sacdev_remarks',
    ];

    protected $casts = [
        'logo_size_bytes' => 'integer',

        'total_org_dev' => 'decimal:2',
        'total_student_services' => 'decimal:2',
        'total_community_involvement' => 'decimal:2',
        'total_overall' => 'decimal:2',

        'submitted_to_moderator_at' => 'datetime',
        'forwarded_to_sacdev_at' => 'datetime',
        'approved_at' => 'datetime',

        'moderator_reviewed_at' => 'datetime',
        'sacdev_reviewed_at' => 'datetime',
    ];

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function targetSchoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'target_school_year_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    public function moderatorReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_reviewed_by');
    }

    public function sacdevReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sacdev_reviewed_by');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(StrategicPlanProject::class, 'submission_id');
    }

    public function fundSources(): HasMany
    {
        return $this->hasMany(StrategicPlanFundSource::class, 'submission_id');
    }

    
    public function orgDevProjects(): HasMany
    {
        return $this->projects()->where('category', StrategicPlanProject::CAT_ORG_DEV);
    }

    public function studentServicesProjects(): HasMany
    {
        return $this->projects()->where('category', StrategicPlanProject::CAT_STUDENT_SERVICES);
    }

    public function communityInvolvementProjects(): HasMany
    {
        return $this->projects()->where('category', StrategicPlanProject::CAT_COMMUNITY_INVOLVEMENT);
    }

    
    public function scopeForSy($query, int $schoolYearId)
    {
        return $query->where('target_school_year_id', $schoolYearId);
    }

    public function scopeForOrg($query, int $orgId)
    {
        return $query->where('organization_id', $orgId);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
