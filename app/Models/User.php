<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'system_role','role_id',
        'must_change_password',
        'password_changed_at',
    ];

    protected $casts = [
        'must_change_password' => 'boolean',
        'password_changed_at' => 'datetime',
    ];

    public function orgMemberships(): HasMany
    {
        return $this->hasMany(OrgMembership::class);
    }

    public function projectAssignments(): HasMany
    {
        return $this->hasMany(ProjectAssignment::class);
    }

    public function isSacdevAdmin(): bool
    {
        return $this->system_role === 'sacdev_admin';
    }
    public function officerEntries()
    {
        return $this->hasMany(\App\Models\OfficerEntry::class);
    }

    public function encodedPresidentRegistrations()
    {
        return $this->hasMany(PresidentRegistration::class, 'encoded_by_user_id');
    }

    public function sacdevReviewedPresidentRegistrations()
    {
        return $this->hasMany(PresidentRegistration::class, 'sacdev_reviewed_by_user_id');
    }

    public function moderatorTerms()
    {
        return $this->hasMany(\App\Models\OrgModeratorTerm::class, 'user_id');
    }

    public function moderatorSubmissions()
    {
        return $this->hasMany(\App\Models\ModeratorSubmission::class, 'moderator_user_id');
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        if (!$this->relationLoaded('role')) {
            $this->load('role');
        }

        return $this->role?->name === $roleName;
    }

    public function hasPermission(string $permissionCode): bool
    {
        if (!$this->relationLoaded('role')) {
            $this->load('role.permissions');
        } elseif ($this->role && !$this->role->relationLoaded('permissions')) {
            $this->role->load('permissions');
        }

        return $this->role?->permissions?->contains('code', $permissionCode) ?? false;
    }

    public function isSacdev(): bool
    {
        return $this->system_role === 'sacdev_admin'
            || $this->hasRole('sacdev')
            || $this->hasRole('super_admin');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function clusters()
    {
        return $this->belongsToMany(\App\Models\Cluster::class, 'cluster_user')
            ->withTimestamps();
    }

    function getSacdevApprover($project)
    {
        $clusterId = $project->organization->cluster_id ?? null;

        if (!$clusterId) {
            return getFallbackAdmin();
        }

        $user = User::whereHas('role', function ($q) {
                $q->where('name', 'sacdev');
            })
            ->whereHas('clusters', function ($q) use ($clusterId) {
                $q->where('clusters.id', $clusterId);
            })
            ->first();

        return $user ?? getFallbackAdmin();
    }


}
