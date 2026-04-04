<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ProjectDocument;
use App\Observers\ProjectDocumentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ProjectDocument::observe(ProjectDocumentObserver::class);
    }
}
