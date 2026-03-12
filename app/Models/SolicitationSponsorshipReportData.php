<?php

namespace App\Models;

use App\Models\SolicitationSponsorshipItem;
use Illuminate\Database\Eloquent\Model;

class SolicitationSponsorshipReportData extends Model
{

    protected $table = 'solicitation_sponsorship_report_data';

    protected $fillable = [

        'project_document_id',
        'activity_name',
        'purpose',
        'solicitation_from',
        'solicitation_to',
        'approved_letters_distributed',

    ];



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }


    public function items()
    {
        return $this->hasMany(
            SolicitationSponsorshipItem::class,
            'solicitation_sponsorship_report_id'
        );
    }

}