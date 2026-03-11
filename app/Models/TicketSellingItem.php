<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketSellingItem extends Model
{

    protected $table = 'ticket_selling_items';

    protected $fillable = [

        'ticket_selling_report_id',
        'quantity',
        'series_control_numbers',
        'price_per_ticket',
        'amount',
        'remarks',

    ];



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function report()
    {
        return $this->belongsTo(
            TicketSellingReportData::class,
            'ticket_selling_report_id'
        );
    }

}