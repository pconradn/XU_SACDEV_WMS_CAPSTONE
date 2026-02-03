<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrgPresidentTerm extends Model
{
    protected $table = 'org_president_terms';

    protected $fillable = [
        'organization_id',
        'school_year_id',
        'user_id',
        'status',
        'start_date',
        'end_date',
        'ended_reason',
        'ended_by_user_id',
        'created_from_registration_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

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
        return $this->belongsTo(User::class);
    }

    public function endedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ended_by_user_id');
    }

    public function createdFromRegistration(): BelongsTo
    {
        return $this->belongsTo(PresidentRegistration::class, 'created_from_registration_id');
    }
}
