<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoaAssignment extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'assigned_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function coaOfficer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}