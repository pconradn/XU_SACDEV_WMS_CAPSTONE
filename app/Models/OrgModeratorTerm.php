<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrgModeratorTerm extends Model
{
    protected $table = 'org_moderator_terms';

    protected $fillable = [
        'organization_id',
        'school_year_id',
        'user_id',
        'created_by_user_id',
        'status',
        'start_date',
        'end_date',
        'ended_reason',
        'ended_by_user_id',
        'activated_at',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'activated_at' => 'datetime',
    ];

    // --------------------
    // Relationships
    // --------------------

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function endedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ended_by_user_id');
    }

    /**
     * One term typically corresponds to one moderator submission (B-5) for that org+SY.
     * (nullable link, but nice to have)
     */
    public function submission(): HasOne
    {
        return $this->hasOne(ModeratorSubmission::class, 'org_moderator_term_id');
    }
}
