<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestToPurchaseItem extends Model
{
    protected $fillable = [
        'request_to_purchase_id',
        'quantity',
        'unit',
        'particulars',
        'unit_price',
        'amount',
        'vendor'
    ];

    public function purchase()
    {
        return $this->belongsTo(RequestToPurchaseData::class, 'request_to_purchase_id');
    }
}