<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class OrgMembership extends Model
{
    use Notifiable;
    
    protected $fillable = [
        'organization_id',
        'school_year_id',
        'user_id',
        'role',
        'archived_at',
        'officer_entry_id',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function officerEntry()
    {
        return $this->belongsTo(\App\Models\OfficerEntry::class, 'officer_entry_id');
    }

}
