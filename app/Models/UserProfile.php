<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProfile extends Model
{
    protected $fillable = [
        'photo_id_path',
        'skills_and_interests',

        'prefix',
        'first_name',
        'middle_initial',
        'last_name',
        'user_id',
        'photo_id_path',
        'full_name',
        'course_and_year',

        'birthday',
        'sex',
        'religion',

        'mobile_number',
        'email',
        'landline',
        'facebook_url',

        'home_address',
        'city_address',

        'father_name',
        'father_occupation',
        'father_mobile',

        'mother_name',
        'mother_occupation',
        'mother_mobile',

        'guardian_name',
        'guardian_relationship',
        'guardian_mobile',

        'siblings_count',

        'high_school_name',
        'high_school_address',
        'high_school_year_graduated',

        'grade_school_name',
        'grade_school_address',
        'grade_school_year_graduated',

        'scholarship_name',
        'scholarship_year_granted',

        'skills_and_interests',

        'university_designation',
        'unit_department',
        'employment_status',
        'years_of_service',
    ];

    protected $casts = [
        'birthday' => 'date',
        'years_of_service' => 'integer',
        'siblings_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaderships(): HasMany
    {
        return $this->hasMany(UserProfileLeadership::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(UserProfileTraining::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function awards(): HasMany
    {
        return $this->hasMany(UserProfileAward::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function isComplete(bool $isModerator = false): bool
    {
        $base = filled($this->full_name)
            && filled($this->birthday)
            && filled($this->mobile_number)
            && filled($this->home_address);

        if (!$isModerator) {
            return $base;
        }

        return $base
            && filled($this->university_designation)
            && filled($this->unit_department)
            && filled($this->employment_status)
            && filled($this->years_of_service);
    }
}