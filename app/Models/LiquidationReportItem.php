<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiquidationReportItem extends Model
{
    protected $table = 'liquidation_report_items';

    protected $fillable = [
        'project_document_id',

        'section_label',

        'date',
        'particulars',
        'amount',

        'source_document_type',
        'source_document_description',

        'or_number',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function document()
    {
        return $this->belongsTo(
            ProjectDocument::class,
            'project_document_id'
        );
    }
}