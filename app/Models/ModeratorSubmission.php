<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModeratorSubmission extends Model
{
    protected $table = 'moderator_submissions';

    protected $fillable = [
        'organization_id',
        'target_school_year_id',
        'moderator_user_id',
        'org_moderator_term_id',

        'status',
        'submitted_at',
        'returned_at',
        'approved_at',

        'sacdev_reviewed_by_user_id',
        'sacdev_remarks',
        'sacdev_reviewed_at',

        'was_moderator_before',
        'moderated_org_name',

        'served_nominating_org_before',
        'served_nominating_org_years',

        'version',

        'edit_requested',
        'edit_requested_at',
        'edit_requested_by_user_id',
        'edit_request_message',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'returned_at'  => 'datetime',
        'approved_at'  => 'datetime',

        'sacdev_reviewed_at' => 'datetime',

        'was_moderator_before' => 'boolean',
        'served_nominating_org_before' => 'boolean',

        'served_nominating_org_years' => 'integer',

        'version' => 'integer',
        'edit_requested' => 'boolean',
        'edit_requested_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function targetSchoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'target_school_year_id');
    }

    public function moderatorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_user_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(OrgModeratorTerm::class, 'org_moderator_term_id');
    }

    public function sacdevReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sacdev_reviewed_by_user_id');
    }

    public function editRequestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edit_requested_by_user_id');
    }

    public function timelines()
    {
        return $this->morphMany(\App\Models\Timeline::class, 'timelineable')->latest();
    }
}