<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficerEntry extends Model
{
    protected $fillable = [
        'organization_id','school_year_id','full_name','email','position','user_id',
        'student_id_number','course_and_year','latest_qpi','mobile_number','sort_order',
        'source_officer_submission_item_id',
    ];

    protected $casts = [
        'latest_qpi' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function membership()
    {
        return $this->hasMany(\App\Models\OrgMembership::class, 'officer_entry_id');
    }
}
