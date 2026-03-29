<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $fillable = [
        'name',
        'acronym',
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'cluster_user')
            ->withTimestamps();
    }
}