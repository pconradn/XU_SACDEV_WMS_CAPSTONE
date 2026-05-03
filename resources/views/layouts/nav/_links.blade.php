@php
use App\Models\SchoolYear;
use App\Models\OrgMembership;
use App\Models\OrganizationSchoolYear;
use App\Models\ProjectAssignment;



$user = auth()->user();
$isAdmin = $user && $user->isSacdev();

$linkClass = function (array $activePatterns) {
    $active = request()->routeIs(...$activePatterns);

    return $active
        ? 'bg-blue-50 text-blue-700 border-blue-200'
        : 'bg-white text-slate-700 border-transparent hover:bg-slate-50 hover:text-slate-900';
};

$item = function (string $label, string $href, array $activePatterns, $badge = null, $icon = null) use ($linkClass) {
    return [
        'label' => $label,
        'href' => $href,
        'class' => $linkClass($activePatterns),
        'badge' => $badge,
        'icon' => $icon,
    ];
};

$dashboardLinks = [];
$contextLinks = [];

if (Route::has('dashboard')) {
    $dashboardLinks[] = $item(
        'Dashboard',
        route('dashboard'),
        ['dashboard'],
        null,
        'dashboard'
    );
}

if (Route::has('context.show') && !$isAdmin) {
    $contextLinks[] = $item(
        'Switch Organization',
        route('context.show'),
        ['context.*', 'org.encode-sy.*'],
        null,
        'folder'
    );
}



$groups = [];





if ($user && $isAdmin) {

    $can = fn ($perm) => $user && ($user->isSuperAdmin() || $user->hasPermission($perm));

    $rereg = [];
    $queues = [];
    $orgTools = [];
    $system = [];

    if (Route::has('admin.rereg.index') && $can('projects.view')) {
        $rereg[] = $item(
            'Manage Submissions',
            route('admin.rereg.index'),
            ['admin.rereg.*','rereg.*'],
            $adminReregBadgeCount ?? null,
            'clipboard'
        );
    }





    //admin.organizations.assign-president
    if (Route::has('admin.orgs_by_sy.index') && $can('projects.view')) {
        $orgTools[] = $item('Manage Organizations', route('admin.orgs_by_sy.index'), ['admin.orgs_by_sy.*']);
    }


    if (Route::has('admin.packets.receive') && $can('documents.manage')) {
        $orgTools[] = $item('Receive Org Packet', route('admin.packets.receive'), ['admin.packets.*']);
    }

    if (Route::has('admin.external-packets.receive') && $can('documents.manage')) {
        $orgTools[] = $item('Receive External Packet', route('admin.external-packets.receive'), ['admin.external-packets.*']);
    }



    if (Route::has('student-clearance.index')) {
        $orgTools[] = $item('Check Student Clearance', route('student-clearance.index'), ['student-clearance.*']);
    }



    if (Route::has('admin.president_assignments.index') && $can('context.manage')) {
        $system[] = $item('Assign President', route('admin.president_assignments.index'), ['admin.president_assignments.*']);
    }

    if (Route::has('admin.organizations.index') && $can('context.manage')) {
        $system[] = $item('Add Organization', route('admin.organizations.index'), ['admin.organizations.*']);
    }

    if (Route::has('admin.school-years.index') && $can('context.manage')) {
        $system[] = $item('Manage School Year', route('admin.school-years.index'), ['admin.school-years.*']);
    }

    if (Route::has('admin.users.index') && $can('users.manage')) {
        $system[] = $item('Manage Admin Users', route('admin.users.index'), ['admin.users.*']);
    }

    if (Route::has('admin.coa.index') && $can('users.manage')) {
        $system[] = $item('Manage COA Officers', route('admin.coa.index'), ['admin.coa.*']);
    }

    if (Route::has('admin.roles.index') && $can('roles.manage')) {
        $system[] = $item('Manage Roles', route('admin.roles.index'), ['admin.roles.*']);
    }

    if (Route::has('admin.audit-logs.index') && $can('projects.view')) {
        $system[] = $item('View Audit Logs', route('admin.audit-logs.index'), ['admin.audit-logs.*']);
    }


    if ($rereg) {
        $groups[] = ['title' => 'Re-Registration Hub', 'links' => $rereg, 'icon' => 'clipboard', 'single' => true];
    }

    if ($queues) {
        $groups[] = ['title' => 'Submission Queues', 'links' => $queues, 'icon' => 'clipboard'];
    }

    if ($orgTools) {
        $groups[] = ['title' => 'Organization Tools', 'links' => $orgTools, 'icon' => 'grid'];
    }

    if ($system) {
        $groups[] = ['title' => 'System Administration', 'links' => $system, 'icon' => 'settings'];
    }
}


$orgLinks = [];
$activeOrgId = (int) session('active_org_id');
$syId = (int) session('encode_sy_id');

if ($user && $activeOrgId && $syId) {
    $orgSyExists = \App\Models\OrganizationSchoolYear::query()
        ->where('organization_id', $activeOrgId)
        ->where('school_year_id', $syId)
        ->exists();

    $stratApproved = \App\Models\StrategicPlanSubmission::query()
        ->where('organization_id', $activeOrgId)
        ->where('target_school_year_id', $syId)
        ->where('status', 'approved_by_sacdev')
        ->exists();

    $officersApproved = \App\Models\OfficerSubmission::query()
        ->where('organization_id', $activeOrgId)
        ->where('target_school_year_id', $syId)
        ->where('status', 'approved_by_sacdev')
        ->exists();


    if (
        Route::has('org.organization-info.show')
        && $stratApproved
        && $officersApproved
    ) {
        $orgLinks[] = $item(
            'Organization',
            route('org.organization-info.show'),
            ['org.organization-info.*'],
            null,
            'house'
        );
    }

    $orgRoles = OrgMembership::query()
        ->where('user_id', $user->id)
        ->where('organization_id', $activeOrgId)
        ->where('school_year_id', $syId)
        ->whereNull('archived_at')
        ->pluck('role')
        ->unique()
        ->values();

    $isPresident = $orgRoles->contains('president');
    $isModerator = $orgRoles->contains('moderator');
    $isTreasurer = $orgRoles->contains('treasurer');
    $isFinance_Officer = $orgRoles->contains('finance_officer');

    $isProjectHead = ProjectAssignment::query()
        ->where('user_id', $user->id)
        ->whereNull('archived_at')
        ->whereHas('project', function ($q) use ($activeOrgId, $syId) {
            $q->where('organization_id', $activeOrgId)
              ->where('school_year_id', $syId);
        })
        ->exists();

    if (!$isPresident) {
        $osyPresident = OrganizationSchoolYear::query()
            ->where('organization_id', $activeOrgId)
            ->where('school_year_id', $syId)
            ->value('president_user_id');

        $isPresident = ((int)$osyPresident === (int)$user->id);
    }


    if (($isPresident || $isModerator) && !$orgSyExists) {
        $rereg = [];

        if (Route::has('org.rereg.index')) {
            $rereg[] = $item(
                'Re-Registration Hub',
                route('org.rereg.index'),
                ['org.rereg.*'],
                null,
                'clipboard'
            );
        }

        if ($rereg) {
            $groups[] = [
                'title' => 'Re-Registration',
                'links' => $rereg,
                'icon' => 'clipboard'
            ];
        }
    }
 

    if ($isPresident){

        $ops = [];

        if (Route::has('org.projects.index')) {
            $ops[] = $item('Manage Projects', route('org.projects.index'), ['org.projects.*']);
        }

        if (Route::has('org.officers.index')) {
            $ops[] = $item('View Officer List', route('org.officers.index'), ['org.officers.*']);
        }

        if (Route::has('org.assign-project-heads.index')) {
            $ops[] = $item('Assign Project Head', route('org.assign-project-heads.index'), ['org.assign-project-heads.*']);
        }

        if (Route::has('org.activation-status.index')) {
            $ops[] = $item('View Accounts Status', route('org.activation-status.index'), ['org.activation-status.*']);
        }






        if ($ops) {
            $groups[] = [
                'title' => 'Operational Modules',
                'links' => $ops,
                'icon' => 'grid'
            ];
        }
    }



    $projectLinks = [];

    if ($isModerator && Route::has('org.projects.index')) {
        $projectLinks[] = $item(
            'Projects',
            route('org.projects.index'),
            ['org.projects.*'],
            null,
            'folder'
        );
    }

    if (($isTreasurer || $isFinance_Officer) && Route::has('org.projects.index')) {
        $projectLinks[] = $item(
            'Projects',
            route('org.projects.index'),
            ['org.projects.*'],
            null,
            'folder'
        );
    }

    if ($isProjectHead && Route::has('org.projects.index')) {
        $projectLinks[] = $item(
            'My Projects',
            route('org.projects.index'),
            ['org.projects.*'],
            null,
            'folder'
        );
    }

    /* remove duplicates */
    if (!empty($projectLinks)) {
        $seen = [];

        $projectLinks = array_values(array_filter($projectLinks, function ($link) use (&$seen) {
            if (in_array($link['label'], $seen, true)) return false;
            $seen[] = $link['label'];
            return true;
        }));

        $groups[] = [
            'title' => 'Projects',
            'links' => $projectLinks,
            'icon' => 'folder' // <- updated
        ];
    }




}
@endphp


@include('layouts.nav._menu', ['links' => $dashboardLinks])

@if ($contextLinks)
@include('layouts.nav._menu', ['links' => $contextLinks])
@endif


@if (!empty($orgLinks))
@include('layouts.nav._menu', ['links' => $orgLinks])
@endif

@foreach ($groups as $group)
@include('layouts.nav.components._group', ['group' => $group])
@endforeach