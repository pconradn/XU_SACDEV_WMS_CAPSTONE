<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitationLetterBatch extends Model
{
    protected $fillable = [
        'project_document_id',
        'approved_letter_count',
        'control_series_start',
        'control_series_end'
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class,'project_document_id');
    }
}