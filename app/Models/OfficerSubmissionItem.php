<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficerSubmissionItem extends Model
{
    protected $table = 'officer_submission_items';

    protected $fillable = [
        'officer_submission_id',

        'position',
        'officer_name',
        'prefix',
        'first_name',
        'middle_initial',
        'last_name',


        'student_id_number',
        'course_and_year',

        'latest_qpi',

        'first_sem_qpi',
        'second_sem_qpi',
        'intersession_qpi',

        'mobile_number',

        'sort_order',

        'is_major_officer',
        'major_officer_role',

        'propagated_to_memberships',
        'propagated_at',
    ];

    protected $casts = [

        'latest_qpi' => 'decimal:2',

        'first_sem_qpi' => 'decimal:2',
        'second_sem_qpi' => 'decimal:2',
        'intersession_qpi' => 'decimal:2',

        'sort_order' => 'integer',

        'is_major_officer' => 'boolean',
        'propagated_to_memberships' => 'boolean',

        'propagated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function submission(): BelongsTo
    {
        return $this->belongsTo(
            OfficerSubmission::class,
            'officer_submission_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Major Officer Helpers
    |--------------------------------------------------------------------------
    */

    public function isMajorOfficer(): bool
    {
        return (bool) $this->is_major_officer;
    }

    public function isPresident(): bool
    {
        return $this->major_officer_role === 'president';
    }

    public function isVicePresident(): bool
    {
        return $this->major_officer_role === 'vice_president';
    }

    public function isTreasurer(): bool
    {
        return $this->major_officer_role === 'treasurer';
    }

    public function isfinance_officer(): bool
    {
        return $this->major_officer_role === 'finance_officer';
    }

    /*
    |--------------------------------------------------------------------------
    | QPI Evaluation Helpers
    |--------------------------------------------------------------------------
    */

    public function getQpis(): array
    {
        return array_filter([
            $this->first_sem_qpi,
            $this->second_sem_qpi,
            $this->intersession_qpi,
        ], fn ($value) => $value !== null);
    }

    public function hasFailingQpi(): bool
    {
        foreach ($this->getQpis() as $qpi) {
            if ($qpi < 2.0) {
                return true;
            }
        }

        return false;
    }

    public function failingQpiCount(): int
    {
        $count = 0;

        foreach ($this->getQpis() as $qpi) {
            if ($qpi < 2.0) {
                $count++;
            }
        }

        return $count;
    }

    public function isUnderProbation(): bool
    {
        return $this->failingQpiCount() >= 2;
    }

    /*
    |--------------------------------------------------------------------------
    | Propagation Helpers
    |--------------------------------------------------------------------------
    */

    public function isPropagated(): bool
    {
        return (bool) $this->propagated_to_memberships;
    }

}