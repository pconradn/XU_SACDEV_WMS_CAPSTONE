<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostponementNoticeData extends Model
{

    protected $fillable = [

        'project_document_id',

        'reason',

        'new_date',
        'new_start_time',
        'new_end_time',

        'venue'

    ];


    public function document()
    {
        return $this->belongsTo(ProjectDocument::class,'project_document_id');
    }

}