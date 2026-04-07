<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'is_archived',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function members()
    {
        return $this->hasMany(OrganizationMemberRecord::class, 'department_id');
    }
}