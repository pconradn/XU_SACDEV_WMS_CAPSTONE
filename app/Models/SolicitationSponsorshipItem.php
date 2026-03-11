<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitationSponsorshipItem extends Model
{

    protected $table = 'solicitation_sponsorship_items';

    protected $fillable = [

        'solicitation_sponsorship_report_id',
        'control_number',
        'person_in_charge',
        'recipient',
        'amount_given',
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
            SolicitationSponsorshipReportData::class,
            'solicitation_sponsorship_report_id'
        );
    }

}