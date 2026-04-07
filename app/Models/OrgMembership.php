<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class OrgMembership extends Model
{
    use Notifiable;
    
    protected $fillable = [
        'organization_id',
        'school_year_id',
        'user_id',
        'role',
        'archived_at',
        'officer_entry_id',
        'is_under_probation',
        'is_suspended',
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
    ];
    public function getSourceAttribute()
    {
        return match ($this->source_type) {
            'officer' => \App\Models\OfficerEntry::find($this->source_id),
            'member' => \App\Models\OrganizationMemberRecord::find($this->source_id),
            default => null,
        };
    }

    public function getDisplayNameAttribute()
    {
        if ($this->source_type === 'member') {
            return $this->source?->full_name;
        }

        return $this->officerEntry?->full_name;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function officerEntry()
    {
        return $this->belongsTo(OfficerEntry::class);
    }

}
