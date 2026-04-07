<?php

use App\Http\Controllers\ContextController;
use App\Http\Controllers\ForcedPasswordController;
use App\Http\Controllers\Org\ClearanceController;
use App\Http\Controllers\ClearanceController as Clearance;
use App\Models\OrgMembership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;


Route::get('/verify/{token}', [\App\Http\Controllers\VerificationController::class, 'show'])
    ->name('verification.show');

Route::get('/ui-test/documents', function () {
    return view('dev.ui.documents-hub-test');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/clearance-check', [Clearance::class, 'index'])
        ->name('student-clearance.index');

    Route::post('/clearance-check/search', [Clearance::class, 'search'])
        ->name('clearance.search');

});

Route::get('/verify-clearance', [Clearance::class, 'publicIndex'])
    ->name('clearance.public.index');

Route::post('/verify-clearance', [Clearance::class, 'publicVerify'])
    ->name('clearance.public.verify');



Route::get('/admin/logs', function () {

    $logPath = storage_path('logs/laravel.log');

    if (!file_exists($logPath)) {
        return "Log file not found.";
    }

    $lines = file($logPath);

    $filtered = array_filter($lines, function ($line) {
        $line = trim($line);

        return !str_starts_with($line, '#') &&
            !str_starts_with($line, '<') &&
            !str_starts_with($line, '[') &&
            !str_starts_with($line, '"');
    });

    

    return response("<pre>" . implode("", $filtered) . "</pre>");

});

/*
|--------------------------------------------------------------------------
| Tests
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return view('test.test');
});



/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('auth.login'));


Route::get(
    '/clearance/verify/{reference}',
    [ClearanceController::class,'verify']
)->name('clearance.verify');


/*
|--------------------------------------------------------------------------
| Auth-only routes (force password change)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/force-change-password', [ForcedPasswordController::class, 'show'])
        ->name('password.force.form');

    Route::post('/force-change-password', [ForcedPasswordController::class, 'update'])
        ->name('password.force.update');

    Route::get('/context', [ContextController::class, 'show'])
        ->name('context.show');

    Route::post('/context', [ContextController::class, 'update'])
        ->name('context.update');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    if ($user->system_role === 'sacdev_admin') {
        return redirect()->route('admin.home');
    }

    
    return redirect()->route('org.home');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Route Modules
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])
        ->name('notifications.markAllRead');

    Route::get('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'show'])
        ->name('notifications.show');
});

Route::get('/test-permission', function () {
    return 'Allowed';
})->middleware('permission:projects.view');


require __DIR__ . '/admin.php';

require __DIR__ . '/org.php';

require __DIR__ . '/auth.php';


