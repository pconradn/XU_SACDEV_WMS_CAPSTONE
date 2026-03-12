<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberList extends Model
{
    protected $table = 'member_lists';

    protected $fillable = [
        'organization_id',
        'target_school_year_id',
        'encoded_by_user_id',
        'certified',
    ];

    protected $casts = [
        'certified' => 'boolean',
    ];

    // --------------------
    // Relationships
    // --------------------

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function targetSchoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'target_school_year_id');
    }

    public function encodedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'encoded_by_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MemberListItem::class, 'member_list_id')
            ->orderBy('sort_order');
    }
}
