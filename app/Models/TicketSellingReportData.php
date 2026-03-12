<?php

namespace App\Models;

use App\Models\TicketSellingItem;
use Illuminate\Database\Eloquent\Model;

class TicketSellingReportData extends Model
{

    protected $table = 'ticket_selling_report_data';

    protected $fillable = [

        'project_document_id',
        'activity_name',
        'selling_from',
        'selling_to',

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
            TicketSellingItem::class,
            'ticket_selling_report_id'
        );
    }

}