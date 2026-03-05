<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffCampusGuidelineAck extends Model
{
    protected $table = 'offcampus_guideline_acks';

    protected $fillable = [
        'project_document_id',
        'user_id',
        'confirmed_at',
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}