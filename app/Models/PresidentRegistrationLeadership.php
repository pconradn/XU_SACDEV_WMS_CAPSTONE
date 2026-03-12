<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresidentRegistrationLeadership extends Model
{
    protected $table = 'president_registration_leaderships';

    protected $fillable = [
        'president_registration_id',
        'organization_name',
        'position',
        'organization_address',
        'inclusive_years',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function presidentRegistration(): BelongsTo
    {
        return $this->belongsTo(PresidentRegistration::class, 'president_registration_id');
    }
}
