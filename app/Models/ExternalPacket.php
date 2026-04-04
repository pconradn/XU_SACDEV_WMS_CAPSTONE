<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalPacket extends Model
{
    protected $fillable = [
        'project_id',
        'destination',
        'reference_no',
        'status',
        'remarks',
        'submitted_at',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(ExternalPacketItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // --- Helpers ---
    public function isEditable(): bool
    {
        return in_array($this->status, ['prepared', 'returned']);
    }

    public function isPrintable(): bool
    {
        return $this->status === 'approved';
    }
    
    public function statusLabel(): string
    {
        return match ($this->status) {
            'prepared' => 'Prepared',
            'submitted' => 'Submitted',
            'approved' => 'Approved',
            'returned' => 'Needs Revision',
            default => ucfirst($this->status),
        };
    }

    public function canTransitionTo($target)
    {
        $map = [
            'prepared' => ['submitted'],
            'submitted' => ['approved', 'returned'],
            'returned' => ['submitted'],
            'approved' => [], // LOCKED
        ];

        return in_array($target, $map[$this->status] ?? []);
    }


    protected static function booted()
    {
        static::creating(function ($packet) {

            // Format: EP-YYYY-XXXX
            $year = now()->format('Y');

            $last = self::whereYear('created_at', $year)
                ->latest('id')
                ->first();

            $nextNumber = $last
                ? ((int) substr($last->reference_no, -4)) + 1
                : 1;

            $packet->reference_no = 'EP-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }


}