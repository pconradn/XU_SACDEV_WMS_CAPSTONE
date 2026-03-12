<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProposalFundSource extends Model
{
    protected $table = 'project_proposal_fund_sources';

    protected $fillable = [
        'project_proposal_data_id',
        'source_name',
        'amount',
    ];

    public function proposal()
    {
        return $this->belongsTo(ProjectProposalData::class, 'project_proposal_data_id');
    }
}