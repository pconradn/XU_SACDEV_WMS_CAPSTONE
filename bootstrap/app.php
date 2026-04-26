<?php


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        channels: __DIR__.'/../routes/channels.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    //add middlewares here 
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->trustProxies('*');

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

            'nocache' => \App\Http\Middleware\NoCache::class,


            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })


    ->withExceptions(function (Exceptions $exceptions) {

        // Handle AuthorizationException (policies, gates, middleware)
        $exceptions->render(function (AuthorizationException $e, $request) {

            // if NOT logged in → redirect to login
            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('error', 'Please log in to access that page.');
            }

            // if logged in → real 403
            return response()->view('errors.403', [
                'exception' => $e
            ], 403);
        });

        // Handle abort(403)
        $exceptions->render(function (HttpException $e, $request) {

            if ($e->getStatusCode() === 401) {

                if (!$request->expectsJson()) {
                    return redirect()->guest(route('login'));
                }

                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // 403 → forbidden → show page
            if ($e->getStatusCode() === 403) {
                return response()->view('errors.403', [
                    'exception' => $e
                ], 403);
            }

            // 419 → session expired
            if ($e->getStatusCode() === 419) {
                return redirect()->route('login');
            }
        });

        $exceptions->render(function (TokenMismatchException $e, $request) {
            return redirect()->route('login');
        });




    })->create();
        //

