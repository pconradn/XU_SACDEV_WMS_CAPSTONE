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

    // Shared
    if (Route::has('dashboard')) {
        $links[] = [
            'label' => 'Dashboard',
            'href'  => route('dashboard'),
            'class' => $linkClass(['dashboard']),
        ];
    }

    if ($user) {

        // =========================
        // ADMIN
        // =========================
        if ($isAdmin) {

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

            if (Route::has('admin.audit-logs.index')) {
                $links[] = [
                    'label' => 'Audit Logs',
                    'href'  => route('admin.audit-logs.index'),
                    'class' => $linkClass(['admin.audit-logs.*']),
                ];
            }

        // =========================
        // ORG / MODERATOR
        // =========================
        } else {

            $activeOrgId = (int) session('active_org_id');

            // Primary context: encode_sy_id (selected SY). Fallback: active SY.
            $syId = (int) session('encode_sy_id');
            $activeSyId = (int) (SchoolYear::activeYear()?->id);

            // Common link: Select SY / context
            if (Route::has('context.show')) {
                $links[] = [
                    'label' => 'Select SY to Encode',
                    'href'  => route('context.show'),
                    'class' => $linkClass(['org.encode-sy.*']),
                ];
            }

            // If no org picked yet, stop here (avoid broken role checks)
            if ($activeOrgId) {

                $orgRole = OrgMembership::query()
                    ->where('user_id', $user->id)
                    ->where('organization_id', $activeOrgId)
                    ->where('school_year_id', $syId)
                    ->whereNull('archived_at')
                    ->value('role');

                $isPresident = ($orgRole === 'president');
                $isModerator = ($orgRole === 'moderator');

                // -------------------------
                // President links
                // -------------------------
                if ($isPresident) {

                    if (Route::has('org.rereg.index')) {
                        $links[] = [
                            'label' => 'Re-Registration Hub',
                            'href'  => route('org.rereg.index'),
                            'class' => $linkClass(['org.rereg.*']),
                        ];
                    }

                    if (Route::has('org.provision.next_president.edit')) {
                        $links[] = [
                            'label' => 'Assign Next SY President',
                            'href'  => route('org.provision.next_president.edit'),
                            'class' => $linkClass(['org.provision.*']),
                        ];
                    }

                    
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

                // -------------------------
                // Moderator links
                // -------------------------
                if ($isModerator) {

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
        }
    }
@endphp

@include('layouts.nav._menu', ['links' => $links])

@auth
    @include('layouts.nav._account', ['user' => $user, 'mode' => $mode ?? 'desktop'])
@endauth
