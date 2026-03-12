<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffCampusActivityData extends Model
{
    protected $fillable = [
        'project_document_id',
        'organization_name',
        'activity_name',
        'inclusive_dates',
        'venue_destination',
        'guidelines_acknowledged_at',
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }

    public function participants()
    {
        return $this->hasMany(OffCampusParticipant::class);
    }
}