<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{

    protected $fillable = [
        'budget_proposal_data_id',
        'section',
        'qty',
        'unit',
        'particulars',
        'price_per_unit',
        'amount'
    ];


    protected $casts = [
        'qty' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'amount' => 'decimal:2'
    ];


    public function budget()
    {
        return $this->belongsTo(BudgetProposalData::class, 'budget_proposal_data_id');
    }

    public function budgetProposalData()
    {
        return $this->belongsTo(\App\Models\BudgetProposalData::class, 'budget_proposal_data_id');
    }

}