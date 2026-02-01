<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrategicPlanProject extends Model
{
    // Category constants
    public const CAT_ORG_DEV = 'org_dev';
    public const CAT_STUDENT_SERVICES = 'student_services';
    public const CAT_COMMUNITY_INVOLVEMENT = 'community_involvement';

    protected $fillable = [
        'submission_id',
        'category',
        'target_date',
        'title',
        'implementing_body',
        'budget',
    ];

    protected $casts = [
        'target_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(StrategicPlanSubmission::class, 'submission_id');
    }

    public function objectives(): HasMany
    {
        return $this->hasMany(StrategicPlanObjective::class, 'project_id');
    }

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(StrategicPlanBeneficiary::class, 'project_id');
    }

    public function deliverables(): HasMany
    {
        return $this->hasMany(StrategicPlanDeliverable::class, 'project_id');
    }

    public function partners(): HasMany
    {
        return $this->hasMany(StrategicPlanPartner::class, 'project_id');
    }
}
