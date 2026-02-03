<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberListItem extends Model
{
    protected $table = 'member_list_items';

    protected $fillable = [
        'member_list_id',
        'full_name',
        'student_id_number',
        'course_and_year',
        'latest_qpi',
        'mobile_number',
        'sort_order',
    ];

    protected $casts = [
        'latest_qpi' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function memberList(): BelongsTo
    {
        return $this->belongsTo(MemberList::class, 'member_list_id');
    }
}
