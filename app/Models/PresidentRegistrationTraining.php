<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresidentRegistrationTraining extends Model
{
    protected $table = 'president_registration_trainings';

    protected $fillable = [
        'president_registration_id',
        'seminar_title',
        'organizer',
        'venue',
        'date_from',
        'date_to',
        'sort_order',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'sort_order' => 'integer',
    ];

    public function presidentRegistration(): BelongsTo
    {
        return $this->belongsTo(PresidentRegistration::class, 'president_registration_id');
    }
}
