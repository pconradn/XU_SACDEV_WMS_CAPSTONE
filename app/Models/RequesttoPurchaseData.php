<?php

namespace App\Models;

use App\Models\RequestToPurchaseItem;
use Illuminate\Database\Eloquent\Model;

class RequestToPurchaseData extends Model
{
    protected $fillable = [
        'project_document_id',

        'xu_finance_amount',
        'membership_fee_amount',
        'pta_amount',
        'solicitations_amount',

        'others_amount',
        'others_label'
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }

    public function items()
    {
        return $this->hasMany(RequestToPurchaseItem::class, 'request_to_purchase_id');
    }
}
