<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalPacketItem extends Model
{
    protected $fillable = [
        'external_packet_id',
        'type',
        'label',
        'form_type_code',
        'document_id',
        'notes',
        'status', 
    ];
    protected $attributes = [
        'status' => 'pending',
    ];

    public function packet()
    {
        return $this->belongsTo(ExternalPacket::class, 'external_packet_id');
    }

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'document_id');
    }
}