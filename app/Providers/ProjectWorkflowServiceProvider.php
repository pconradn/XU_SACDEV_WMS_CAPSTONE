<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ProjectWorkflowService;

class ProjectWorkflowServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(ProjectWorkflowService::class, function ($app) {
            return new ProjectWorkflowService();
        });
    }

    public function boot(): void
    {
        //
    }
}