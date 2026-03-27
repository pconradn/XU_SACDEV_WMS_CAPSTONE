<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'acronym',
        'mission',
        'vision',
        'logo_path',
        'logo_original_name',
        'logo_mime',
        'logo_size_bytes',
        'last_b1_submission_id',
    ];

    protected $casts = [
        'logo_size_bytes' => 'integer',
    ];

    public function schoolYears(): HasMany
    {
        return $this->hasMany(OrganizationSchoolYear::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(OrgMembership::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function officerEntries(): HasMany
    {
        return $this->hasMany(OfficerEntry::class);
    }
    
    public function presidentRegistrations()
    {
        return $this->hasMany(PresidentRegistration::class);
    }

    public function presidentTerms()
    {
        return $this->hasMany(OrgPresidentTerm::class);
    }
    
    public function officerSubmissions()
    {
        return $this->hasMany(\App\Models\OfficerSubmission::class);
    }
    
    public function memberLists()
    {
        return $this->hasMany(\App\Models\MemberList::class);
    }

    public function moderatorTerms()
    {
        return $this->hasMany(\App\Models\OrgModeratorTerm::class);
    }

    public function moderatorSubmissions()
    {
        return $this->hasMany(\App\Models\ModeratorSubmission::class);
    }

    public function lastStrategicPlanSubmission()
    {
        return $this->belongsTo(StrategicPlanSubmission::class, 'last_b1_submission_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('not_archived', function ($query) {
            $query->whereNull('archived_at');
        });
    }

    public function cluster()
    {
        return $this->belongsTo(\App\Models\Cluster::class);
    }


}
