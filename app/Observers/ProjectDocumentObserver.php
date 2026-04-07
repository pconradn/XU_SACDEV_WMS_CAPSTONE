<?php

namespace App\Observers;

use App\Models\ProjectDocument;
use App\Services\ProjectWorkflowService;

class ProjectDocumentObserver
{
    public function updated(ProjectDocument $document): void
    {

        if (
            $document->wasChanged('status') ||
            $document->wasChanged('updated_at')
        ) {
            app(\App\Services\ProjectWorkflowService::class)
                ->updateFromDocument($document);
        }
    }

    public function created(ProjectDocument $document): void
    {
      
        if ($document->status === 'draft') {
            app(ProjectWorkflowService::class)
                ->updateFromDocument($document);
        }
    }
}