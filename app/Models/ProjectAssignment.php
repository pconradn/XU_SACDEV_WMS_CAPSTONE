<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectAssignment extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'assignment_role',
        'archived_at',
        'agreement_accepted_at',
        'agreement_ip',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function membership()
    {
        return $this->hasOne(\App\Models\OrgMembership::class, 'user_id', 'user_id');
    }

    public function scopeDraftees($query)
    {
        return $query->where('assignment_role', 'draftee')
                    ->whereNull('archived_at');
    }


    public function officerEntry()
    {
        return $this->hasOneThrough(
            \App\Models\OfficerEntry::class,
            \App\Models\OrgMembership::class,
            'user_id',    
            'id',                
            'user_id',        
            'officer_entry_id'    
        );
    }

    public function hasAcceptedAgreement(): bool
    {
        return !is_null($this->agreement_accepted_at);
    }

    public function memberRecord()
    {
        return $this->hasOneThrough(
            \App\Models\OrganizationMemberRecord::class,
            \App\Models\OrgMembership::class,
            'user_id',
            'id',
            'user_id',
            'source_id'
        )->where('org_memberships.source_type', 'member');
    }

    public function getDisplayNameAttribute()
    {
        if ($this->memberRecord) {
            return $this->memberRecord->getFullNameAttribute();
        }

        if ($this->officerEntry) {
            return $this->officerEntry->full_name;
        }

        return $this->user?->name;
    }

}
