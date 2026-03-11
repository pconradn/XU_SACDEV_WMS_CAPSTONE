<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionPacketDv extends Model
{
    protected $table = 'submission_packet_dvs';

    protected $fillable = [
        'packet_id',
        'dv_reference',
        'dv_label',
        'amount',
        'remarks'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function packet()
    {
        return $this->belongsTo(
            SubmissionPacket::class,
            'packet_id'
        );
    }
}