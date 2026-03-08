<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
    ];

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

}
