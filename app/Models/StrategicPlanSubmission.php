<?php

namespace App\Models;

use App\Models\Timeline;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrategicPlanSubmission extends Model
{
    // Status constant
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED_TO_MODERATOR = 'submitted_to_moderator';
    public const STATUS_RETURNED_BY_MODERATOR = 'returned_by_moderator';
    public const STATUS_FORWARDED_TO_SACDEV = 'forwarded_to_sacdev';
    public const STATUS_RETURNED_BY_SACDEV = 'returned_by_sacdev';
    public const STATUS_APPROVED_BY_SACDEV = 'approved_by_sacdev';
    public const STATUS_SUBMITTED_TO_SACDEV = 'submitted_to_sacdev';


    protected $fillable = [
        'organization_id',
        'target_school_year_id',
        'submitted_by_user_id',

        'status',

        'org_acronym',
        'org_name',
        'mission',
        'vision',

        'logo_path',
        'logo_original_name',
        'logo_mime',
        'logo_size_bytes',

        'total_org_dev',
        'total_student_services',
        'total_community_involvement',
        'total_overall',

        'submitted_to_moderator_at',
        'forwarded_to_sacdev_at',
        'approved_at',

        'moderator_reviewed_by',
        'moderator_reviewed_at',
        'moderator_remarks',

        'sacdev_reviewed_by',
        'sacdev_reviewed_at',
        'sacdev_remarks',
    ];

    protected $casts = [
        'logo_size_bytes' => 'integer',

        'total_org_dev' => 'decimal:2',
        'total_student_services' => 'decimal:2',
        'total_community_involvement' => 'decimal:2',
        'total_overall' => 'decimal:2',

        'submitted_to_moderator_at' => 'datetime',
        'forwarded_to_sacdev_at' => 'datetime',
        'approved_at' => 'datetime',

        'moderator_reviewed_at' => 'datetime',
        'sacdev_reviewed_at' => 'datetime',
    ];

    private function cleanArray(array $items): array
    {
        return collect($items)
            ->map(fn ($v) => is_string($v) ? trim($v) : $v)
            ->filter(fn ($v) => !is_null($v) && $v !== '')
            ->values()
            ->toArray();
    }

    public function syncFundSources(array $fundSourcesPayload): void
    {
        $this->fundSources()->delete();

        foreach ($fundSourcesPayload as $fs) {

            $type = $fs['type'] ?? null;
            $amount = $fs['amount'] ?? null;

            if (!$type || $amount === null) {
                continue;
            }

            $this->fundSources()->create([
                'type'   => $type,
                'label'  => trim($fs['label'] ?? ''),
                'amount' => $amount,
            ]);
        }
    }

    public function recomputeTotals(): void
    {
        $projects = $this->projects;

        $orgDev  = $projects->where('category', StrategicPlanProject::CAT_ORG_DEV)->sum('budget');
        $stud    = $projects->where('category', StrategicPlanProject::CAT_STUDENT_SERVICES)->sum('budget');
        $comm    = $projects->where('category', StrategicPlanProject::CAT_COMMUNITY_INVOLVEMENT)->sum('budget');

        $this->update([
            'total_org_dev' => $orgDev,
            'total_student_services' => $stud,
            'total_community_involvement' => $comm,
            'total_overall' => $orgDev + $stud + $comm,
        ]);
    }


    public function syncAll(array $projects, array $fundSources): void
    {
        $this->syncProjects($projects);
        $this->syncFundSources($fundSources);
        $this->load('projects');
        $this->recomputeTotals();
    }

    public function syncProjects(array $projectsPayload): void
    {
        $this->projects()->each(function ($p) {
            $p->objectives()->delete();
            $p->beneficiaries()->delete();
            $p->deliverables()->delete();
            $p->partners()->delete();
        });

        $this->projects()->delete();

        foreach ($projectsPayload as $proj) {

            $title = trim($proj['title'] ?? '');

            if ($title === '') {
                continue;
            }

            $objectives     = $this->cleanArray($proj['objectives'] ?? []);
            $beneficiaries  = $this->cleanArray($proj['beneficiaries'] ?? []);
            $deliverables   = $this->cleanArray($proj['deliverables'] ?? []);
            $partners       = $this->cleanArray($proj['partners'] ?? []);

            if (empty($objectives) || empty($beneficiaries) || empty($deliverables)) {
                continue;
            }

            $project = $this->projects()->create([
                'category'          => $proj['category'] ?? null,
                'target_date'       => $proj['target_date'] ?? null,
                'title'             => $title,
                'implementing_body' => trim($proj['implementing_body'] ?? ''),
                'budget'            => $proj['budget'] ?? 0,
            ]);

            foreach ($objectives as $text) {
                $project->objectives()->create(['text' => $text]);
            }

            foreach ($beneficiaries as $text) {
                $project->beneficiaries()->create(['text' => $text]);
            }

            foreach ($deliverables as $text) {
                $project->deliverables()->create(['text' => $text]);
            }

            foreach ($partners as $text) {
                $project->partners()->create(['text' => $text]);
            }
        }
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function targetSchoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'target_school_year_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    public function moderatorReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_reviewed_by');
    }

    public function sacdevReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sacdev_reviewed_by');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(StrategicPlanProject::class, 'submission_id');
    }

    public function fundSources(): HasMany
    {
        return $this->hasMany(StrategicPlanFundSource::class, 'submission_id');
    }

    
    public function orgDevProjects(): HasMany
    {
        return $this->projects()->where('category', StrategicPlanProject::CAT_ORG_DEV);
    }

    public function studentServicesProjects(): HasMany
    {
        return $this->projects()->where('category', StrategicPlanProject::CAT_STUDENT_SERVICES);
    }

    public function communityInvolvementProjects(): HasMany
    {
        return $this->projects()->where('category', StrategicPlanProject::CAT_COMMUNITY_INVOLVEMENT);
    }

    
    public function scopeForSy($query, int $schoolYearId)
    {
        return $query->where('target_school_year_id', $schoolYearId);
    }

    public function scopeForOrg($query, int $orgId)
    {
        return $query->where('organization_id', $orgId);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
    public function timelines()
    {
        return $this->morphMany(Timeline::class, 'timelineable')->latest();
    }


}
