<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffCampusParticipant extends Model
{
    protected $fillable = [
        'off_campus_activity_data_id',
        'student_name',
        'course_year',
        'student_mobile',
        'parent_name',
        'parent_mobile',
    ];

    public function activity()
    {
        return $this->belongsTo(OffCampusActivityData::class);
    }
}