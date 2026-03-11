<?php

namespace App\Models;

use App\Models\SubmissionPacketReceipt;
use Illuminate\Database\Eloquent\Model;

class SubmissionPacket extends Model
{
    protected $table = 'submission_packets';

    protected $fillable = [

        'packet_code',

        'project_id',
        'project_document_id',

        'has_liquidation_report',
        'has_disbursement_voucher',
        'has_collection_report',
        'has_certificates',
        'has_receipts',

        'status',

        'generated_by',
        'generated_at',

        'submitted_at',

        'received_by',
        'received_at',

        'verified_by',
        'verified_at',

        'forwarded_at',
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

    public function receipts()
    {
        return $this->hasMany(
            SubmissionPacketReceipt::class,
            'packet_id'
        );
    }

    public function dvs()
    {
        return $this->hasMany(
            SubmissionPacketDv::class,
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

    public function verifiedBy()
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }
}