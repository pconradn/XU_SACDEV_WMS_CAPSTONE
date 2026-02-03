<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficerSubmissionItem extends Model
{
    protected $table = 'officer_submission_items';

    protected $fillable = [
        'officer_submission_id',
        'position',
        'officer_name',
        'student_id_number',
        'course_and_year',
        'latest_qpi',
        'mobile_number',
        'sort_order',
    ];

    protected $casts = [
        'latest_qpi' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(OfficerSubmission::class, 'officer_submission_id');
    }
}
