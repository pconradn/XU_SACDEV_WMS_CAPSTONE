@php
use App\Models\SchoolYear;
use App\Models\OrgMembership;
use App\Models\OrganizationSchoolYear;
use App\Models\ProjectAssignment;

$user = auth()->user();
$isAdmin = $user && $user->system_role === 'sacdev_admin';

$linkClass = function (array $activePatterns) {
    $active = request()->routeIs(...$activePatterns);

    return $active
        ? 'bg-blue-50 text-blue-700 border-blue-200'
        : 'bg-white text-slate-700 border-transparent hover:bg-slate-50 hover:text-slate-900';
};

$item = function (string $label, string $href, array $activePatterns, $badge = null) use ($linkClass) {
    return [
        'label' => $label,
        'href' => $href,
        'class' => $linkClass($activePatterns),
        'badge' => $badge,
    ];
};

$dashboardLinks = [];
$contextLinks = [];
$groups = [];



if (Route::has('dashboard')) {
    $dashboardLinks[] = $item('Dashboard', route('dashboard'), ['dashboard']);
}

if (Route::has('context.show') && !$isAdmin) {
    $contextLinks[] = $item(
        'Select SY / Organization',
        route('context.show'),
        ['context.*', 'org.encode-sy.*']
    );
}



if ($user && $isAdmin) {

    $rereg = [];
    $queues = [];
    $orgTools = [];
    $system = [];

    if (Route::has('admin.rereg.index')) {
        $rereg[] = $item(
            'Re-Registration Hub',
            route('admin.rereg.index'),
            ['admin.rereg.*','rereg.*'],
            $adminReregBadgeCount ?? null
        );
    }

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

    if (Route::has('admin.review.index')) {
        $orgTools[] = $item('Organization Review', route('admin.review.index'), ['admin.review.*']);
    }

    if (Route::has('admin.packets.receive')) {
        $orgTools[] = $item('Packet Receiving', route('admin.packets.receive'), ['admin.packets.*']);
    }

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
        $orgTools[] = $item('Organizations (by SY)', route('admin.orgs_by_sy.index'), ['admin.orgs_by_sy.*']);
    }

    if (Route::has('admin.audit-logs.index')) {
        $system[] = $item('Audit Logs', route('admin.audit-logs.index'), ['admin.audit-logs.*']);
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



$activeOrgId = (int) session('active_org_id');
$syId = (int) session('encode_sy_id');

if ($user && $activeOrgId && $syId) {

    $orgRole = OrgMembership::query()
        ->where('user_id', $user->id)
        ->where('school_year_id', $syId)
        ->whereNull('archived_at')
        ->value('role');

    $isPresident = ($orgRole === 'president');
    $isModerator = ($orgRole === 'moderator');
    $isTreasurer = ($orgRole === 'treasurer');
    $isAuditor = ($orgRole === 'auditor');

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

    /* ========================= */
    /* PRESIDENT */
    /* ========================= */
    if ($isPresident) {

        $rereg = [];
        $ops = [];

        if (Route::has('org.rereg.index')) {
            $rereg[] = $item('Re-Registration Hub', route('org.rereg.index'), ['org.rereg.*']);
        }

        if (Route::has('org.provision.next_president.edit')) {
            $rereg[] = $item('Assign Next SY President', route('org.provision.next_president.edit'), ['org.provision.*']);
        }

        if (Route::has('org.officers.index')) {
            $ops[] = $item('Officer List', route('org.officers.index'), ['org.officers.*']);
        }

        if (Route::has('org.projects.index')) {
            $ops[] = $item('Projects', route('org.projects.index'), ['org.projects.*']);
        }

        if (Route::has('org.assign-roles.edit')) {
            $ops[] = $item('Assign Treasurer / Moderator', route('org.assign-roles.edit'), ['org.assign-roles.*']);
        }

        if (Route::has('org.assign-project-heads.index')) {
            $ops[] = $item('Assign Project Heads', route('org.assign-project-heads.index'), ['org.assign-project-heads.*']);
        }

        if (Route::has('org.activation-status.index')) {
            $ops[] = $item('Activation Status', route('org.activation-status.index'), ['org.activation-status.*']);
        }

        if ($rereg) {
            $groups[] = [
                'title' => 'Re-Registration',
                'links' => $rereg,
                'icon' => 'clipboard'
            ];
        }

        if ($ops) {
            $groups[] = [
                'title' => 'Operational Modules',
                'links' => $ops,
                'icon' => 'grid'
            ];
        }
    }


    /* ========================= */
    /* PROJECT ACCESS (ALL ROLES) */
    /* ========================= */

    $projectLinks = [];

    if ($isModerator && Route::has('org.projects.index')) {
        $projectLinks[] = $item('Projects', route('org.projects.index'), ['org.projects.*']);
    }

    if (($isTreasurer || $isAuditor) && Route::has('org.projects.index')) {
        $projectLinks[] = $item('Projects', route('org.projects.index'), ['org.projects.*']);
    }

    if ($isProjectHead && Route::has('org.projects.index')) {
        $projectLinks[] = $item('My Projects', route('org.projects.index'), ['org.projects.*']);
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
            'icon' => 'clipboard'
        ];
    }


    /* ========================= */
    /* MODERATOR TOOLS */
    /* ========================= */

    if ($isModerator) {
        $mod = [];

        if (Route::has('org.moderator.rereg.dashboard')) {
            $mod[] = $item(
                'Re-Registration Dashboard',
                route('org.moderator.rereg.dashboard'),
                ['org.moderator.rereg.*']
            );
        }

        if (Route::has('org.moderator.strategic_plans.index')) {
            $mod[] = $item(
                'Review Strategic Plans (B-1)',
                route('org.moderator.strategic_plans.index'),
                ['org.moderator.strategic_plans.*']
            );
        }

        if ($mod) {
            $groups[] = [
                'title' => 'Moderator',
                'links' => $mod,
                'icon' => 'user'
            ];
        }
    }

}
@endphp


@include('layouts.nav._menu', ['links' => $dashboardLinks])

@if ($contextLinks)
@include('layouts.nav._menu', ['links' => $contextLinks])
@endif

@foreach ($groups as $group)
@include('layouts.nav.components._group', ['group' => $group])
@endforeach