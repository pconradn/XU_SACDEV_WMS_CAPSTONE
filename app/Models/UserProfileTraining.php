<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfileTraining extends Model
{
    protected $fillable = [
        'user_profile_id',
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

    public function profile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id');
    }
}