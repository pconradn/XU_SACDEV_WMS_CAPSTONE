@php
    use App\Models\SchoolYear;
    use App\Models\OrgMembership;

    $linkClass = function (array $activePatterns) {
        $active = request()->routeIs(...$activePatterns);

        return $active
            ? 'bg-blue-50 text-blue-700 border-blue-200'
            : 'bg-white text-slate-700 border-transparent hover:bg-slate-50 hover:text-slate-900';
    };

    $links = [];

    // ==========================================================
    // Shared links (admin + org)
    // ==========================================================
    if (Route::has('dashboard')) {
        $links[] = [
            'label' => 'Dashboard',
            'href'  => route('dashboard'),
            'class' => $linkClass(['dashboard']),
        ];
    }

    // ==========================================================
    // Role-based links
    // ==========================================================
    if ($user) {

        // -------------------------
        // ADMIN
        // -------------------------
    if ($isAdmin) {

        // Re-registration overview (SY + org picker)
        if (Route::has('admin.rereg.index')) {
            $links[] = [
                'label' => 'Re-Registration',
                'href'  => route('admin.rereg.index'),
                'class' => $linkClass(['admin.rereg.*', 'rereg.*']),
            ];
        }

        if (Route::has('admin.school-years.index')) {
            $links[] = [
                'label' => 'School Years',
                'href'  => route('admin.school-years.index'),
                'class' => $linkClass(['admin.school-years.*']),
            ];
        }

        if (Route::has('admin.organizations.index')) {
            $links[] = [
                'label' => 'Organizations',
                'href'  => route('admin.organizations.index'),
                'class' => $linkClass(['admin.organizations.*']),
            ];
        }

        // Keep if you still actively use this for auditing actions
        if (Route::has('admin.audit-logs.index')) {
            $links[] = [
                'label' => 'Audit Logs',
                'href'  => route('admin.audit-logs.index'),
                'class' => $linkClass(['admin.audit-logs.*']),
            ];
        }

    } else {

        // Basic context
        $activeSyId  = SchoolYear::activeYear()?->id;
        $activeOrgId = session('active_org_id');

        $orgRole = null;
        if ($activeSyId && $activeOrgId) {
            $orgRole = OrgMembership::where('user_id', $user->id)
                ->where('school_year_id', $activeSyId)
                ->where('organization_id', $activeOrgId)
                ->whereNull('archived_at')
                ->value('role');
        }

        $isPresident = ($orgRole === 'president');

        // IMPORTANT: $isModerator should be computed elsewhere (your existing logic)
        // $isModerator = ...

        /*
        |---------------------------------------------------------
        | Common links (Org portal)
        |---------------------------------------------------------
        */


        if (Route::has('context.show')) {
            $links[] = [
                'label' => 'Select SY to Encode',
                'href'  => route('context.show'),
                'class' => $linkClass(['context.show.*']),
            ];
        }

        /*
        |---------------------------------------------------------
        | President links
        |---------------------------------------------------------
        */
        if ($isPresident) {

            // Re-registration hub (SY2 president work)
            if (Route::has('org.rereg.index')) {
                $links[] = [
                    'label' => 'Re-Registration Hub',
                    'href'  => route('org.rereg.index'),
                    'class' => $linkClass(['org.rereg.*']),
                ];
            }

            // Provisioning (Active SY president only). Route exists, middleware will enforce.
            if (Route::has('org.provision.next_president.edit')) {
                $links[] = [
                    'label' => 'Assign Next SY President',
                    'href'  => route('org.provision.next_president.edit'),
                    'class' => $linkClass(['org.provision.*']),
                ];
            }

            // Operational modules (active SY)
            if (Route::has('org.officers.index')) {
                $links[] = [
                    'label' => 'Officer List',
                    'href'  => route('org.officers.index'),
                    'class' => $linkClass(['org.officers.*']),
                ];
            }

            if (Route::has('org.projects.index')) {
                $links[] = [
                    'label' => 'Projects',
                    'href'  => route('org.projects.index'),
                    'class' => $linkClass(['org.projects.*']),
                ];
            }

            if (Route::has('org.assign-roles.edit')) {
                $links[] = [
                    'label' => 'Assign Treasurer/Moderator',
                    'href'  => route('org.assign-roles.edit'),
                    'class' => $linkClass(['org.assign-roles.*']),
                ];
            }

            if (Route::has('org.assign-project-heads.index')) {
                $links[] = [
                    'label' => 'Assign Project Heads',
                    'href'  => route('org.assign-project-heads.index'),
                    'class' => $linkClass(['org.assign-project-heads.*']),
                ];
            }

            if (Route::has('org.activation-status.index')) {
                $links[] = [
                    'label' => 'Activation Status',
                    'href'  => route('org.activation-status.index'),
                    'class' => $linkClass(['org.activation-status.*']),
                ];
            }
        }
    }
    /*
    |---------------------------------------------------------
    | Moderator links
    |---------------------------------------------------------
    */
    if (($isModerator ?? false)) {

        if (Route::has('org.moderator.rereg.dashboard')) {
            $links[] = [
                'label' => 'Moderator Re-Registration',
                'href'  => route('org.moderator.rereg.dashboard'),
                'class' => $linkClass(['org.moderator.rereg.*']),
            ];
        }

        if (Route::has('org.moderator.strategic_plans.index')) {
            $links[] = [
                'label' => 'Moderator Review (B-1)',
                'href'  => route('org.moderator.strategic_plans.index'),
                'class' => $linkClass(['org.moderator.strategic_plans.*']),
            ];
        }

        if (Route::has('org.moderator.rereg.b5.index')) {
            $links[] = [
                'label' => 'B-5 Moderator Form',
                'href'  => route('org.moderator.rereg.b5.index'),
                'class' => $linkClass(['org.moderator.rereg.b5.*']),
            ];
        }
    }
}

@endphp

@include('layouts.nav._menu', ['links' => $links])

@auth
    @include('layouts.nav._account', ['user' => $user, 'mode' => $mode ?? 'desktop'])
@endauth

<div class="text-xs text-red-600">
    isModerator: {{ isset($isModerator) ? ($isModerator ? 'true' : 'false') : 'NOT SET' }}
</div>
