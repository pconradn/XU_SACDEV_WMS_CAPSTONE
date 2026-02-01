<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategicPlanFundSource extends Model
{
    public const TYPE_ORG_FUNDS = 'org_funds';
    public const TYPE_AECO = 'aeco';
    public const TYPE_PTA = 'pta';
    public const TYPE_MEMBERSHIP_FEE = 'membership_fee';
    public const TYPE_RAISED_FUNDS = 'raised_funds';
    public const TYPE_OTHER = 'other';

    protected $fillable = [
        'submission_id',
        'type',
        'label',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(StrategicPlanSubmission::class, 'submission_id');
    }
}
