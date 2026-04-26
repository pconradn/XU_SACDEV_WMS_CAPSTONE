<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionPacketItem extends Model
{
    protected $table = 'submission_packet_items';

    protected $fillable = [
        'packet_id',
        'type',
        'reference_number',
        'label',
        'amount',
        'organization_name',
        'remarks',
        'review_status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function packet()
    {
        return $this->belongsTo(SubmissionPacket::class, 'packet_id');
    }
}