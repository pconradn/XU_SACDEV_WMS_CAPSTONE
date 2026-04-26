<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationSchoolYear extends Model
{
    protected $fillable = [
        'organization_id',
        'school_year_id',
        'president_user_id',
        'president_confirmed_at',
    ];

    protected $casts = [
        'president_confirmed_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function president(): BelongsTo
    {
        return $this->belongsTo(User::class, 'president_user_id');
    }

    
}
