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

            if (Route::has('admin.strategic_plans.index')) {
                $links[] = [
                    'label' => 'Strategic Plans',
                    'href'  => route('admin.strategic_plans.index'),
                    'class' => $linkClass([
                        'admin.strategic_plans.*',
                    ]),
                ];
            }

            if (Route::has('admin.review.index')) {
                $links[] = [
                    'label' => 'Review Submissions',
                    'href'  => route('admin.review.index'),
                    'class' => $linkClass(['admin.review.*']),
                ];
            }

            if (Route::has('admin.audit-logs.index')) {
                $links[] = [
                    'label' => 'Audit Logs',
                    'href'  => route('admin.audit-logs.index'),
                    'class' => $linkClass(['admin.audit-logs.*']),
                ];
            }

            if (Route::has('admin.b2.president.index')) {
                $links[] = [
                    'label' => 'B-2 President Registrations',
                    'href'  => route('admin.b2.president.index'),
                    'class' => $linkClass(['admin.b2.president.*']),
                ];
            }

            if (Route::has('admin.officer_submissions.index')) {
                $links[] = [
                    'label' => 'B-3 Officer Submissions',
                    'href'  => route('admin.officer_submissions.index'),
                    'class' => $linkClass(['admin.officer_submissions.*']),
                ];
            }

            if (Route::has('admin.member_lists.index')) {
                $links[] = [
                    'label' => 'B-4 Members Lists',
                    'href'  => route('admin.member_lists.index'),
                    'class' => $linkClass(['admin.member_lists.*']),
                ];
            }



        // -------------------------
        // ORG
        // -------------------------
        } else {

            $activeSyId = SchoolYear::activeYear()?->id;
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

            


       
            if ($isPresident) {

                if (Route::has('org.encode-sy.show')) {
                    $links[] = [
                        'label' => 'Select SY to Encode',
                        'href'  => route('org.encode-sy.show'),
                        'class' => $linkClass(['org.encode-sy.show']),
                    ];
                }

                if (Route::has('org.b2.president.index')) {
                    $links[] = [
                        'label' => 'B-2 President Registration',
                        'href'  => route('org.b2.president.index'),
                        'class' => $linkClass(['org.b2.president.*']),
                    ];
                }

                if (Route::has('org.b3.officers-list.index')) {
                    $links[] = [
                        'label' => 'B-3 Officers List',
                        'href'  => route('org.b3.officers-list.index'),
                        'class' => $linkClass(['org.b3.officers-list.*']),
                    ];
                }

                if (Route::has('org.b4.members-list.index')) {
                    $links[] = [
                        'label' => 'B-4 Members List',
                        'href'  => route('org.b4.members-list.index'),
                        'class' => $linkClass(['org.b4.members-list.*']),
                    ];
                }


                if (Route::has('org.strategic_plan.select_sy')) {
                    $links[] = [
                        'label' => 'Strategic Plan Submission',
                        'href'  => route('org.strategic_plan.select_sy'),
                        'class' => $linkClass(['org.strategic-plan.*']),
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

               
                if (Route::has('org.assignments.index')) {
                    $links[] = [
                        'label' => 'Assign Roles',
                        'href'  => route('org.assignments.index'),
                        'class' => $linkClass(['org.assignments.*']),
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
        

            if (($isModerator ?? false) && Route::has('org.moderator.strategic_plans.index')) {
                $links[] = [
                    'label' => 'Moderator Review',
                    'href'  => route('org.moderator.strategic_plans.index'),
                    'class' => $linkClass(['org.moderator.strategic_plans.*']),
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
