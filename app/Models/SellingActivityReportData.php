<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingActivityReportData extends Model
{
    protected $table = 'selling_activity_report_data';

    protected $fillable = [
        'project_document_id',
        'activity_name',
        'selling_from',
        'selling_to',
    ];


    public function document()
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }

    public function items()
    {
        return $this->hasMany(SellingActivityReportItem::class, 'selling_activity_report_id');
    }
}