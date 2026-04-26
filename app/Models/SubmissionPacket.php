<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionPacket extends Model
{
    protected $table = 'submission_packets';

    protected $fillable = [
        'packet_code',
        'project_id',
        'project_document_id',

        'status',
        'remarks',
        'return_remarks',

        'generated_by',
        'generated_at',

        'submitted_at',

        'received_by',
        'received_at',

        'returned_by',
        'returned_at',

        'reviewed_at',
        'ready_for_claiming_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'submitted_at' => 'datetime',
        'received_at' => 'datetime',
        'returned_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'ready_for_claiming_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

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
            SubmissionPacketItem::class,
            'packet_id'
        );
    }

    public function generatedBy()
    {
        return $this->belongsTo(
            User::class,
            'generated_by'
        );
    }

    public function receivedBy()
    {
        return $this->belongsTo(
            User::class,
            'received_by'
        );
    }

    public function returnedBy()
    {
        return $this->belongsTo(
            User::class,
            'returned_by'
        );
    }
}