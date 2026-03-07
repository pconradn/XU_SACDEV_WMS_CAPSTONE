<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingApplicationData extends Model
{

    protected $fillable = [

        'project_document_id',
        'activity_name',
        'purpose',
        'duration_from',
        'duration_to',
        'projected_sales'

    ];

    public function items()
    {
        return $this->hasMany(SellingApplicationItem::class);
    }

}
