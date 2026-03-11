<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionPacketLetter extends Model
{
    protected $fillable = [
        'packet_id',
        'control_number',
        'organization_name'
    ];

    public function packet()
    {
        return $this->belongsTo(SubmissionPacket::class);
    }
}