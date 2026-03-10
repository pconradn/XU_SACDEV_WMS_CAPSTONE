<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeesCollectionReportData extends Model
{

    protected $table = 'fees_collection_report_data';

    protected $fillable = [
        'project_document_id',
        'activity_name',
        'purpose',
        'collection_from',
        'collection_to'
    ];


    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }


    public function items()
    {
        return $this->hasMany(
            FeesCollectionItem::class,
            'fees_collection_report_id'
        );
    }

}