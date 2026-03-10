<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeesCollectionItem extends Model
{

    protected $table = 'fees_collection_items';

    protected $fillable = [
        'fees_collection_report_id',
        'number_of_payers',
        'amount_paid',
        'receipt_series',
        'remarks'
    ];


    public function report()
    {
        return $this->belongsTo(
            FeesCollectionReportData::class,
            'fees_collection_report_id'
        );
    }

}