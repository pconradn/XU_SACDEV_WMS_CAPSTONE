<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    //add middlewares here 
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'must_change_password' => \App\Http\Middleware\MustChangePassword::class,
            'sacdev_admin' => \App\Http\Middleware\RequireSacdevAdmin::class,
            'active_sy_access' => \App\Http\Middleware\RequireActiveSYAccess::class,
            'president_encode' => \App\Http\Middleware\RequirePresidentEncodeContext::class,
            'org.moderator' => \App\Http\Middleware\EnsureOrgModerator::class,
            'org.ctx'  => \App\Http\Middleware\OrgContextMiddleware::class,
            'org.role' => \App\Http\Middleware\OrgRoleMiddleware::class,
            'require_president_active_sy' => \App\Http\Middleware\RequirePresidentActiveSy::class,
            'require.context' => \App\Http\Middleware\RequireContext::class,
            'operational_access' => \App\Http\Middleware\OperationalAccess::class,
            'project.access' => \App\Http\Middleware\EnsureUserCanAccessProject::class,
            'project.role' => \App\Http\Middleware\RequireProjectRole::class,
            'document.type' => \App\Http\Middleware\EnsureDocumentType::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
