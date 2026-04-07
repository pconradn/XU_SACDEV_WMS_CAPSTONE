<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationMemberRecord extends Model
{
    protected $fillable = [
        'organization_id',
        'school_year_id',
        'user_id',

        'first_name',
        'last_name',
        'middle_initial',
        'latest_qpi',

        'email',
        'student_id_number',
        'course_and_year',
        'mobile_number',

        'encoded_by',
        'archived_at',

        'department_id'
    ];

    protected $casts = [
        'archived_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function encoder()
    {
        return $this->belongsTo(User::class, 'encoded_by');
    }

    public function getFullNameAttribute()
    {
        return trim(
            $this->last_name . ', ' .
            $this->first_name .
            ($this->middle_initial ? ' ' . $this->middle_initial . '.' : '')
        );
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}