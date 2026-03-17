@php
use App\Models\SchoolYear;
use App\Models\OrgMembership;
use App\Models\OrganizationSchoolYear;

$user = auth()->user();
$isAdmin = $user && $user->system_role === 'sacdev_admin';


/*
|--------------------------------------------------------------------------
| Link Styling
|--------------------------------------------------------------------------
*/

$linkClass = function (array $activePatterns) {

    $active = request()->routeIs(...$activePatterns);

    return $active
        ? 'bg-blue-50 text-blue-700 border-blue-200'
        : 'bg-white text-slate-700 border-transparent hover:bg-slate-50 hover:text-slate-900';

};


/*
|--------------------------------------------------------------------------
| Link Builder
|--------------------------------------------------------------------------
*/

$item = function (string $label, string $href, array $activePatterns, $badge = null) use ($linkClass) {

    return [
        'label' => $label,
        'href' => $href,
        'class' => $linkClass($activePatterns),
        'badge' => $badge,
    ];

};


/*
|--------------------------------------------------------------------------
| Navigation Containers
|--------------------------------------------------------------------------
*/

$dashboardLinks = [];
$contextLinks = [];
$groups = [];


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

if (Route::has('dashboard')) {

    $dashboardLinks[] = $item(
        'Dashboard',
        route('dashboard'),
        ['dashboard']
    );

}


/*
|--------------------------------------------------------------------------
| Context Selector
|--------------------------------------------------------------------------
*/

if (Route::has('context.show') && !$isAdmin) {

    $contextLinks[] = $item(
        'Select SY / Organization',
        route('context.show'),
        ['context.*', 'org.encode-sy.*']
    );

}


/*
|--------------------------------------------------------------------------
| ADMIN NAVIGATION
|--------------------------------------------------------------------------
*/

if ($user && $isAdmin) {

    $rereg = [];
    $queues = [];
    $orgTools = [];
    $system = [];


    /*
    |--------------------------------------------------------------------------
    | Re-Registration Hub (single entry)
    |--------------------------------------------------------------------------
    */

    if (Route::has('admin.rereg.index')) {

        $rereg[] = $item(
            'Re-Registration Hub',
            route('admin.rereg.index'),
            ['admin.rereg.*','rereg.*'],
            $adminReregBadgeCount ?? null
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Submission Queues
    |--------------------------------------------------------------------------
    */

    if (Route::has('admin.strategic_plans.index')) {
        $queues[] = $item('Strategic Plans (B-1)', route('admin.strategic_plans.index'), ['admin.strategic_plans.*']);
    }

    if (Route::has('admin.b2.president.index')) {
        $queues[] = $item('President Registrations (B-2)', route('admin.b2.president.index'), ['admin.b2.president.*']);
    }

    if (Route::has('admin.officer_submissions.index')) {
        $queues[] = $item('Officer Submissions (B-3)', route('admin.officer_submissions.index'), ['admin.officer_submissions.*']);
    }

    if (Route::has('admin.member_lists.index')) {
        $queues[] = $item('Member Lists (B-4)', route('admin.member_lists.index'), ['admin.member_lists.*']);
    }

    if (Route::has('admin.moderator_submissions.index')) {
        $queues[] = $item('Moderator Submissions (B-5)', route('admin.moderator_submissions.index'), ['admin.moderator_submissions.*']);
    }


    /*
    |--------------------------------------------------------------------------
    | Organization Tools
    |--------------------------------------------------------------------------
    */

    if (Route::has('admin.review.index')) {
        $orgTools[] = $item('Organization Review', route('admin.review.index'), ['admin.review.*']);
    }

    if (Route::has('admin.packets.receive')) {
        $orgTools[] = $item('Packet Receiving', route('admin.packets.receive'), ['admin.packets.*']);
    }


    /*
    |--------------------------------------------------------------------------
    | System Administration
    |--------------------------------------------------------------------------
    */

    if (Route::has('admin.school-years.index')) {
        $system[] = $item('School Years', route('admin.school-years.index'), ['admin.school-years.*']);
    }

    if (Route::has('admin.organizations.index')) {
        $system[] = $item('Organizations', route('admin.organizations.index'), ['admin.organizations.*']);
    }

    if (Route::has('admin.president_assignments.index')) {
        $system[] = $item('President Assignments', route('admin.president_assignments.index'), ['admin.president_assignments.*']);
    }

    if (Route::has('admin.orgs_by_sy.index')) {
        $system[] = $item('Organizations (by SY)', route('admin.orgs_by_sy.index'), ['admin.orgs_by_sy.*']);
    }

    if (Route::has('admin.audit-logs.index')) {
        $system[] = $item('Audit Logs', route('admin.audit-logs.index'), ['admin.audit-logs.*']);
    }


    /*
    |--------------------------------------------------------------------------
    | Push Navigation Groups
    |--------------------------------------------------------------------------
    */


    // Re-Registration (single item, not dropdown)
    if ($rereg) {
        $groups[] = [
            'title' => 'Re-Registration Hub',
            'links' => $rereg,
            'icon' => 'clipboard',
            'single' => true
        ];
    }


    if ($queues) {
        $groups[] = [
            'title' => 'Submission Queues',
            'links' => $queues,
            'icon' => 'clipboard'
        ];
    }


    if ($orgTools) {
        $groups[] = [
            'title' => 'Organization Tools',
            'links' => $orgTools,
            'icon' => 'grid'
        ];
    }


    if ($system) {
        $groups[] = [
            'title' => 'System Administration',
            'links' => $system,
            'icon' => 'settings'
        ];
    }

}



@endphp


{{-- Dashboard --}}
@include('layouts.nav._menu', ['links' => $dashboardLinks])

{{-- Context --}}
@if ($contextLinks)
@include('layouts.nav._menu', ['links' => $contextLinks])
@endif



@foreach ($groups as $group)

@include('layouts.nav.components._group', [
    'group' => $group
])

@endforeach