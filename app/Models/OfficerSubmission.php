<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfficerSubmission extends Model
{
    protected $table = 'officer_submissions';

    protected $fillable = [
        'organization_id',
        'target_school_year_id',
        'encoded_by_user_id',
        'status',
        'certified',                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                

        'sacdev_reviewed_by_user_id',
        'sacdev_remarks',
        'submitted_at',
        'returned_at',
        'approved_at',
        'sacdev_reviewed_at',

        'edit_requested',
        'edit_request_reason',
        'edit_requested_by_user_id',
        'edit_requested_at',
    ];

    protected $casts = [
        'certified' => 'boolean',
        'edit_requested' => 'boolean',

        'submitted_at' => 'datetime',
        'returned_at' => 'datetime',
        'approved_at' => 'datetime',
        'sacdev_reviewed_at' => 'datetime',
        'edit_requested_at' => 'datetime',
    ];

    // Relationships
    // --------------------

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function targetSchoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'target_school_year_id');
    }

    public function encodedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'encoded_by_user_id');
    }

    public function sacdevReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sacdev_reviewed_by_user_id');
    }

    public function editRequestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edit_requested_by_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OfficerSubmissionItem::class, 'officer_submission_id')
            ->orderBy('sort_order');
    }

    // --------------------
    // Helpers 
    // --------------------

    public function isEditableByOrg(): bool
    {
        return in_array($this->status, ['draft', 'returned_by_sacdev'], true);
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted_to_sacdev';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved_by_sacdev';
    }
    public function timelines()
    {
        return $this->morphMany(Timeline::class, 'timelineable')->latest();
    }
}
