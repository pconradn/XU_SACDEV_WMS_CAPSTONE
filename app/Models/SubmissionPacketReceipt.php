<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionPacketReceipt extends Model
{
    protected $table = 'submission_packet_receipts';

    protected $fillable = [
        'packet_id',
        'or_number'
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