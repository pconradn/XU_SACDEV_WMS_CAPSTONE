<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresidentRegistrationAward extends Model
{
    protected $table = 'president_registration_awards';

    protected $fillable = [
        'president_registration_id',
        'award_name',
        'award_description',
        'conferred_by',
        'date_received',
        'sort_order',
    ];

    protected $casts = [
        'date_received' => 'date',
        'sort_order' => 'integer',
    ];

    public function presidentRegistration(): BelongsTo
    {
        return $this->belongsTo(PresidentRegistration::class, 'president_registration_id');
    }
}
