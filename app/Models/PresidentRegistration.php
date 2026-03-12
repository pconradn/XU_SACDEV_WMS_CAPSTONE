<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PresidentRegistration extends Model
{
    protected $table = 'president_registrations';

    protected $fillable = [
        'organization_id',
        'target_school_year_id',
        'encoded_by_user_id',
        'status',
        'submitted_at',
        'returned_at',
        'approved_at',

        'sacdev_reviewed_by_user_id',
        'sacdev_remarks',
        'sacdev_reviewed_at',

        'version',

        'photo_id_path',
        'full_name',
        'course_and_year',

        'birthday',
        'age',
        'sex',
        'religion',

        'mobile_number',
        'city_landline',
        'email',
        'id_number',
        'provincial_landline',
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
        'certified',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'returned_at' => 'datetime',
        'approved_at' => 'datetime',
        'sacdev_reviewed_at' => 'datetime',
        'birthday' => 'date',
        'certified' => 'boolean',
        'siblings_count' => 'integer',
        'age' => 'integer',
        'version' => 'integer',
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

    public function sacdevReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sacdev_reviewed_by_user_id');
    }

    public function leaderships(): HasMany
    {
        return $this->hasMany(PresidentRegistrationLeadership::class, 'president_registration_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(PresidentRegistrationTraining::class, 'president_registration_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function awards(): HasMany
    {
        return $this->hasMany(PresidentRegistrationAward::class, 'president_registration_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function createdPresidentTerms(): HasMany
    {
        // Useful when you later approve and create terms linked to this registration
        return $this->hasMany(OrgPresidentTerm::class, 'created_from_registration_id');
    }

    // --------------------
    // Optional helpers
    // --------------------

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved_by_sacdev';
    }

    


}
