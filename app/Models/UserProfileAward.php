<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfileAward extends Model
{
    protected $fillable = [
        'user_profile_id',
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

    public function profile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id');
    }
}