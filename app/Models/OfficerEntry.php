<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficerEntry extends Model
{
    protected $fillable = [
        'organization_id',
        'school_year_id',
        'full_name',
        'email',
        'position',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
