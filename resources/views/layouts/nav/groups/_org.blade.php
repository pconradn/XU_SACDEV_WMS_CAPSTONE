@php

$activeOrgId = (int) session('active_org_id');

$syId = (int) session('encode_sy_id');
$activeSyId = (int) (SchoolYear::activeYear()?->id);

if ($activeOrgId) {

    $orgRole = OrgMembership::query()
        ->where('user_id', $user->id)
        ->where('school_year_id', $syId)
        ->whereNull('archived_at')
        ->value('role');

    $isAuditor = ($orgRole === 'auditor');
    $isTreasurer = ($orgRole === 'treasurer');
    $isModerator = ($orgRole === 'moderator');
    $isPresident = ($orgRole === 'president');

    if (!$isPresident) {
        $osyPresident = OrganizationSchoolYear::query()
            ->where('organization_id', $activeOrgId)
            ->where('school_year_id', $syId)
            ->value('president_user_id');

        $isPresident = ((int)$osyPresident === (int)$user->id);
    }

    /*
    |--------------------------------
    | PRESIDENT MODULES
    |--------------------------------
    */

    if ($isPresident) {

        $rereg = [];
        $ops = [];

        if (Route::has('org.rereg.index')) {
            $rereg[] = $item('Re-Registration Hub', route('org.rereg.index'), ['org.rereg.*']);
        }

        if (Route::has('org.provision.next_president.edit')) {
            $rereg[] = $item(
                'Assign Next SY President',
                route('org.provision.next_president.edit'),
                ['org.provision.*']
            );
        }

        if (Route::has('org.officers.index')) {
            $ops[] = $item('Officer List', route('org.officers.index'), ['org.officers.*']);
        }

        if (Route::has('org.projects.index')) {
            $ops[] = $item('Projects', route('org.projects.index'), ['org.projects.*']);
        }

        if (Route::has('org.assign-roles.edit')) {
            $ops[] = $item(
                'Assign Treasurer / Moderator',
                route('org.assign-roles.edit'),
                ['org.assign-roles.*']
            );
        }

        if (Route::has('org.assign-project-heads.index')) {
            $ops[] = $item(
                'Assign Project Heads',
                route('org.assign-project-heads.index'),
                ['org.assign-project-heads.*']
            );
        }

        if (Route::has('org.activation-status.index')) {
            $ops[] = $item(
                'Activation Status',
                route('org.activation-status.index'),
                ['org.activation-status.*']
            );
        }

        if (!empty($rereg)) {
            $groups[] = [
                'key' => 'org_rereg',
                'title' => 'Re-Registration',
                'links' => $rereg,
                'icon' => 'clipboard'
            ];
        }

        if (!empty($ops)) {
            $groups[] = [
                'key' => 'org_ops',
                'title' => 'Operational Modules',
                'links' => $ops,
                'icon' => 'grid'
            ];
        }

    }

    /*
    |--------------------------------
    | PROJECT HEAD ACCESS
    |--------------------------------
    */

    $isProjectHead = \App\Models\ProjectAssignment::query()
        ->where('user_id', $user->id)
        ->whereNull('archived_at')
        ->whereHas('project', function ($q) use ($activeOrgId, $syId) {
            $q->where('organization_id', $activeOrgId)
              ->where('school_year_id', $syId);
        })
        ->exists();

    if ($isProjectHead) {

        $ph = [];

        if (Route::has('org.projects.index')) {
            $ph[] = $item(
                'My Projects',
                route('org.projects.index'),
                ['org.projects.*']
            );
        }

        if (!empty($ph)) {
            $groups[] = [
                'key' => 'org_project_head',
                'title' => 'Project Head',
                'links' => $ph,
                'icon' => 'clipboard'
            ];
        }

    }

    /*
    |--------------------------------
    | TREASURER ACCESS
    |--------------------------------
    */

    if ($isTreasurer || $isAuditor) {

        $treasurer = [];

        if (Route::has('org.projects.index')) {
            $treasurer[] = $item(
                'Projects',
                route('org.projects.index'),
                ['org.projects.*']
            );
        }

        if (!empty($treasurer)) {
            $groups[] = [
                'key' => 'org_treasurer',
                'title' => 'Treasurer',
                'links' => $treasurer,
                'icon' => 'clipboard'
            ];
        }

    }

    /*
    |--------------------------------
    | MODERATOR ACCESS
    |--------------------------------
    */

    if ($isModerator) {

        $modProjects = [];

        if (Route::has('org.projects.index')) {
            $modProjects[] = $item(
                'Projects',
                route('org.projects.index'),
                ['org.projects.*']
            );
        }

        if (!empty($modProjects)) {
            $groups[] = [
                'key' => 'org_mod_projects',
                'title' => 'Moderator Projects',
                'links' => $modProjects,
                'icon' => 'clipboard'
            ];
        }

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

        if (!empty($mod)) {
            $groups[] = [
                'key' => 'org_mod',
                'title' => 'Moderator',
                'links' => $mod,
                'icon' => 'user'
            ];
        }

    }

}

@endphp