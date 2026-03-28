<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFormRequirement extends Model
{
    protected $fillable = [
        'form_type_id',
        'rule_key',
        'label',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: requirement belongs to a form type
     */
    public function formType(): BelongsTo
    {
        return $this->belongsTo(FormType::class);
    }
}