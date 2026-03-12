<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategicPlanPartner extends Model
{
    protected $fillable = [
        'project_id',
        'text',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(StrategicPlanProject::class, 'project_id');
    }
}
