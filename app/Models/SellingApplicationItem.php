<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingApplicationItem extends Model
{

    protected $fillable = [

        'selling_application_data_id',
        'quantity',
        'particulars',
        'selling_price',
        'remarks'

    ];

    public function application()
    {
        return $this->belongsTo(SellingApplicationData::class);
    }

}