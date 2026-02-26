<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocumentSignature extends Model
{
    protected $fillable = [
        'project_document_id',
        'user_id',
        'role',
        'status',
        'signed_at',
        'remarks',
    ];

    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}