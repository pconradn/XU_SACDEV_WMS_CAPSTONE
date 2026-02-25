<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgConstitutionSubmission extends Model
{
    protected $fillable = [
        'organization_id',
        'school_year_id',
        'file_path',
        'original_filename',
        'submitted_by_user_id',
        'submitted_at',
        'status',
        'remarks',
        'approved_by_user_id',
        'approved_at',
    ];


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }
}
