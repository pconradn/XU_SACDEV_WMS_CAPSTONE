<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingActivityReportItem extends Model
{
    protected $table = 'selling_activity_report_items';

    protected $fillable = [
        'selling_activity_report_id',
        'quantity',
        'particulars',
        'price',
        'amount',
        'acknowledgement_receipt_number',
    ];

    
    public function report()
    {
        return $this->belongsTo(SellingActivityReportData::class, 'selling_activity_report_id');
    }
}