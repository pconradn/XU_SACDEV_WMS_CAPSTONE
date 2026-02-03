<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModeratorSubmissionLeadership extends Model
{
    protected $table = 'moderator_submission_leaderships';

    protected $fillable = [
        'moderator_submission_id',
        'organization_name',
        'position',
        'organization_address',
        'inclusive_years',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ModeratorSubmission::class, 'moderator_submission_id');
    }
}
