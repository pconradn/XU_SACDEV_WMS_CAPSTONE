<?php

namespace App\Models;

use App\Models\BudgetItem;
use Illuminate\Database\Eloquent\Model;

class BudgetProposalData extends Model
{
    protected $table = 'budget_proposal_data';

    protected $fillable = [

        'project_document_id',

        'counterpart_amount_per_pax',
        'counterpart_pax',
        'counterpart_total',

        'pta_amount',

        'raised_funds',

        'amount_charged_to_org',
        'section_totals',
        'total_expenses'
    ];

    protected $casts = [
        'section_totals' => 'array',
        'total_expenses' => 'decimal:2'
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }

    public function items()
    {
        return $this->hasMany(BudgetItem::class);
    }
}