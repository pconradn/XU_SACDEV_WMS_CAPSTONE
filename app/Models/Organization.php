<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = ['name', 'acronym'];

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
}
