<?php

namespace App\Models;

use App\Models\LiquidationReportItem;
use Illuminate\Database\Eloquent\Model;

class LiquidationReportData extends Model
{
    protected $table = 'liquidation_report_data';

    protected $fillable = [
        'project_document_id',

        'contact_number',

        'finance_amount',
        'fund_raising_amount',
        'sacdev_amount',
        'pta_amount',

        'fundraising_types', // UPDATED (was fundraising_type)

        'total_funds',
        'total_expenses',
        'total_advanced',
        'balance',

        'cluster_a_return',
        'cluster_b_return',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'fundraising_types' => 'array', 
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

    public function items()
    {
        return $this->hasMany(
            LiquidationReportItem::class,
            'project_document_id',
            'project_document_id'
        );
    }
}