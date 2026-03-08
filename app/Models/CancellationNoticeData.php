<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancellationNoticeData extends Model
{

    protected $fillable = [

        'project_document_id',

        'reason'

    ];


    public function document()
    {
        return $this->belongsTo(ProjectDocument::class,'project_document_id');
    }

}