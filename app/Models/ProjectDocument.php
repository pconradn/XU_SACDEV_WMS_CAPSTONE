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

    // -----------------------------
    // Approval Chain (per form)
    // -----------------------------
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

    // -----------------------------
    // Lock rule: once moderator signed
    // -----------------------------
    public function isLocked(): bool
    {
        return $this->signatures()
            ->where('role', 'moderator')
            ->where('status', 'signed')
            ->exists();
    }

    // -----------------------------
    // Next role to approve
    // -----------------------------
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
}