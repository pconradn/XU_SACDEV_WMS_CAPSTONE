<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OfficerEntry extends Model
{
    protected $fillable = [
        'organization_id',
        'school_year_id',

        'full_name',
        'email',

        // display title (Prime Minister, etc)
        'position',

        // system authority role (president, treasurer, etc)
        'major_officer_role',
        'is_major_officer',

        'user_id',

        'student_id_number',
        'course_and_year',

        'first_sem_qpi',
        'second_sem_qpi',
        'intersession_qpi',

        'is_under_probation',

        'mobile_number',
        'sort_order',

        'source_officer_submission_item_id',
    ];

    protected $casts = [
        'sort_order' => 'integer',

        'is_major_officer' => 'boolean',
        'is_under_probation' => 'boolean',

        'first_sem_qpi' => 'decimal:2',
        'second_sem_qpi' => 'decimal:2',
        'intersession_qpi' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeMajorOfficers(Builder $query): Builder
    {
        return $query->where('is_major_officer', true);
    }

    public function scopePresidents(Builder $query): Builder
    {
        return $query->where('major_officer_role', 'president');
    }

    public function scopeTreasurers(Builder $query): Builder
    {
        return $query->where('major_officer_role', 'treasurer');
    }

    public function scopeForSchoolYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    public function scopeForOrganization(Builder $query, int $orgId): Builder
    {
        return $query->where('organization_id', $orgId);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPresident(): bool
    {
        return $this->major_officer_role === 'president';
    }

    public function isTreasurer(): bool
    {
        return $this->major_officer_role === 'treasurer';
    }

    public function isMajorOfficer(): bool
    {
        return $this->is_major_officer === true;
    }

    public function hasFailingQPI(): bool
    {
        return collect([
            $this->first_sem_qpi,
            $this->second_sem_qpi,
            $this->intersession_qpi,
        ])
        ->filter()
        ->contains(fn ($qpi) => $qpi < 2.0);
    }

    public function failingQPICount(): int
    {
        return collect([
            $this->first_sem_qpi,
            $this->second_sem_qpi,
            $this->intersession_qpi,
        ])
        ->filter()
        ->filter(fn ($qpi) => $qpi < 2.0)
        ->count();
    }

    public function shouldBeUnderProbation(): bool
    {
        return $this->failingQPICount() >= 2;
    }

    public function computeProbationStatus(): void
    {
        $this->is_under_probation = $this->shouldBeUnderProbation();
    }

}