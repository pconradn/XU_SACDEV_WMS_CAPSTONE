<?php

namespace App\Models;

use App\Models\Timeline;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProjectDocument extends Model
{
    protected $fillable = [
        'project_id',
        'form_type_id',
        'created_by_user_id',
        'status',
        'submitted_at',
        'reviewed_at',
        'reviewed_by_user_id',
        'remarks',
        'archived_at',


        'edit_requested',
        'edit_requested_at',
        'edit_requested_by',
        'edit_request_remarks',
        'edit_mode',
        'edit_requires_full_approval',



    ];

    public function editRequestedBy()
    {
        return $this->belongsTo(User::class, 'edit_requested_by');
    }

    public function timelines()
    {
        return $this->morphMany(Timeline::class, 'timelineable')->latest();
    }


    public function getCanRequestEditAttribute(): bool
    {
        return $this->status === 'approved' && !$this->edit_requested;
    }

    public function getHasPendingEditRequestAttribute(): bool
    {
        return (bool) $this->edit_requested;
    }

    public function getIsInEditModeAttribute(): bool
    {
        return (bool) $this->edit_mode;
    }

    public function getCanEditContentAttribute(): bool
    {
        return $this->status === 'draft' || $this->edit_mode;
    }



    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function formType()
    {
        return $this->belongsTo(FormType::class);
    }

    public function signatures()
    {
        return $this->hasMany(ProjectDocumentSignature::class);
    }

    public function proposalData()
    {
        return $this->hasOne(ProjectProposalData::class, 'project_document_id');
    }

    public function budgetProposal()
    {
        return $this->hasOne(BudgetProposalData::class);
    }


    public function approvalChain(): array
    {
        $code = $this->formType?->code;

        return match ($code) {
            'project_proposal' => [
                'project_head',
                'treasurer',
                'finance_officer',
                'president',
                'moderator',
                'sacdev_admin',
            ],
            default => [],
        };
    }


    public function isLocked(): bool
    {
        return $this->signatures()
            ->where('role', 'moderator')
            ->where('status', 'signed')
            ->exists();
    }

    public function nextPendingRole(): ?string
    {
        $chain = $this->approvalChain();
        if (empty($chain)) return null;

        $signedRoles = $this->signatures()
            ->where('status', 'signed')
            ->pluck('role')
            ->all();

        foreach ($chain as $role) {
            if (!in_array($role, $signedRoles, true)) {
                return $role;
            }
        }

        return null; 
    }


    public function budgetProposalData()
    {
        return $this->hasOne(\App\Models\BudgetProposalData::class);
    }

    
    public function offCampusActivity()
    {
        return $this->hasOne(OffCampusActivityData::class);
    }


    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }


    public function solicitationData()
    {
        return $this->hasOne(SolicitationApplicationData::class);
    }


    public function solicitationBatches()
    {
        return $this->hasMany(
            \App\Models\SolicitationLetterBatch::class,
            'project_document_id'
        );
    }


    public function sellingApplication()
    {
        return $this->hasOne(\App\Models\SellingApplicationData::class, 'project_document_id');
    }


    public function requestToPurchase()
    {
        return $this->hasOne(\App\Models\RequestToPurchaseData::class, 'project_document_id');
    }


    public function postponementNotice()
    {
        return $this->hasOne(\App\Models\PostponementNoticeData::class,'project_document_id');
    }


    public function cancellationNotice()
    {
        return $this->hasOne(\App\Models\CancellationNoticeData::class,'project_document_id');
    }


    public function nextPendingRole2()
    {
        $sig = $this->signatures()
            ->where('status','pending')
            ->orderBy('id')
            ->first();

        return $sig?->role;
    }

    public function feesCollectionReport()
    {
        return $this->hasOne(
            \App\Models\FeesCollectionReportData::class,
            'project_document_id'
        );
    }


    public function sellingActivityReport()
    {
        return $this->hasOne(SellingActivityReportData::class, 'project_document_id');
    }


    public function solicitationSponsorshipReport()
    {
        return $this->hasOne(
            \App\Models\SolicitationSponsorshipReportData::class,
            'project_document_id'
        );
    }

    public function ticketSellingReport()
    {
        return $this->hasOne(
            \App\Models\TicketSellingReportData::class,
            'project_document_id'
        );
    }

    public function documentationReport()
    {
        return $this->hasOne(
            \App\Models\DocumentationReportData::class,
            'project_document_id'
        );
    }

    public function documentationReportObjectives()
    {
        return $this->hasMany(
            \App\Models\DocumentationReportObjective::class,
            'project_document_id'
        );
    }

    public function documentationReportIndicators()
    {
        return $this->hasMany(
            \App\Models\DocumentationReportIndicator::class,
            'project_document_id'
        );
    }


    public function documentationReportPartners()
    {
        return $this->hasMany(
            \App\Models\DocumentationReportPartner::class,
            'project_document_id'
        );
    }

    public function documentationReportAttendees()
    {
        return $this->hasMany(
            \App\Models\DocumentationReportAttendee::class,
            'project_document_id'
        );
    }

    public function liquidationData()
    {
        return $this->hasOne(
            LiquidationReportData::class,
            'project_document_id'
        );
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'returned' => 'Returned',
            'approved_by_moderator' => 'Approved by Moderator',
            'approved_by_sacdev' => 'Approved',
            default => str_replace('_', ' ', ucfirst($this->status ?? 'unknown')),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-slate-100 text-slate-700 ring-slate-200',
            'submitted' => 'bg-blue-100 text-blue-700 ring-blue-200',
            'returned' => 'bg-orange-100 text-orange-700 ring-orange-200',
            'approved_by_moderator' => 'bg-indigo-100 text-indigo-700 ring-indigo-200',
            'approved_by_sacdev' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            default => 'bg-slate-100 text-slate-700 ring-slate-200',
        };
    }


    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'returned', 'submitted']);
    }

    public function currentPendingSignature()
    {
        return $this->signatures()
            ->where('status', 'pending')
            ->orderBy('id')
            ->first();
    }

    protected static function booted()
    {
        static::creating(function ($doc) {
            if (!$doc->verification_token) {
                $doc->verification_token = (string) Str::uuid();
            }
        });
    }

    public function getVerificationUrlAttribute(): string
    {
        return route('verification.show', $this->verification_token);
    }

}
