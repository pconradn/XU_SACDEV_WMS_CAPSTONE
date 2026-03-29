<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'phase',
        'description',
    ];

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function requirements()
    {
        return $this->hasMany(ProjectFormRequirement::class);
    }
}