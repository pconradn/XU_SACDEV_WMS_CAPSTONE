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
        'system_role',
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
}
