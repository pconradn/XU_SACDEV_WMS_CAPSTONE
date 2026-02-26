@php
    use App\Models\SchoolYear;
    use App\Models\OrgMembership;
    use App\Models\OrganizationSchoolYear;

    $linkClass = function (array $activePatterns) {
        $active = request()->routeIs(...$activePatterns);

        return $active
            ? 'bg-blue-50 text-blue-700 border-blue-200'
            : 'bg-white text-slate-700 border-transparent hover:bg-slate-50 hover:text-slate-900';
    };

    $item = function (string $label, string $href, array $activePatterns, $badge = null) use ($linkClass) {
        return [
            'label' => $label,
            'href'  => $href,
            'class' => $linkClass($activePatterns),
            'badge'  => $badge,
        ];
    };

    $dashboardLinks = [];
    $contextLinks = [];

    if (Route::has('dashboard')) {
        $dashboardLinks[] = $item('Dashboard', route('dashboard'), ['dashboard']);
    }


    if (Route::has('context.show') && !$isAdmin) {
        $contextLinks[] = $item('Select SY / Organization', route('context.show'), ['context.*', 'org.encode-sy.*']);
    }

    $groups = [];

    if ($user) {

        // =========================
        // ADMIN
        // =========================
        if ($isAdmin) {
            $home = [];
            $rereg = [];
            $queues = [];
            $admin = [];
            $orgTools = [];

            // Home / dashboard
            if (Route::has('admin.home')) {
                $home[] = $item('Admin Dashboard', route('admin.home'), ['admin.home']);
            }

            // Re-registration hub flow
            if (Route::has('admin.rereg.index')) {
                $rereg[] = $item(
                    'Re-Registration Hub',
                    route('admin.rereg.index'),
                    ['admin.rereg.*', 'rereg.*'],
                    $adminReregBadgeCount ?? null
                );
            }

            // Review / tools (non-form)
            if (Route::has('admin.review.index')) {
                $orgTools[] = $item('Organization Review', route('admin.review.index'), ['admin.review.*']);
            }

            // Form queues (indexes)
            if (Route::has('admin.strategic_plans.index')) {
                $queues[] = $item('B-1 Strategic Plans', route('admin.strategic_plans.index'), ['admin.strategic_plans.*']);
            }

            if (Route::has('admin.b2.president.index')) {
                $queues[] = $item('B-2 President Registrations', route('admin.b2.president.index'), ['admin.b2.president.*']);
            }

            if (Route::has('admin.officer_submissions.index')) {
                $queues[] = $item('B-3 Officer Submissions', route('admin.officer_submissions.index'), ['admin.officer_submissions.*']);
            }

            if (Route::has('admin.member_lists.index')) {
                $queues[] = $item('B-4 Member Lists', route('admin.member_lists.index'), ['admin.member_lists.*']);
            }

            if (Route::has('admin.moderator_submissions.index')) {
                $queues[] = $item('B-5 Moderator Submissions', route('admin.moderator_submissions.index'), ['admin.moderator_submissions.*']);
            }

            // Administration
            if (Route::has('admin.school-years.index')) {
                $admin[] = $item('School Years', route('admin.school-years.index'), ['admin.school-years.*']);
            }

            if (Route::has('admin.organizations.index')) {
                $admin[] = $item('Organizations', route('admin.organizations.index'), ['admin.organizations.*']);
            }

            // Elections / Provisioning
            if (Route::has('admin.president_assignments.index')) {
                $admin[] = $item(
                    'President Assignments',
                    route('admin.president_assignments.index'),
                    ['admin.president_assignments.*']
                );
            }

            if (Route::has('admin.organizations.assign-president')) {
                $admin[] = $item(
                    'Assign President (Legacy)',
                    route('admin.organizations.assign-president'),
                    ['admin.organizations.assign-president*']
                );
                // includes both GET and POST names
            }

            if (Route::has('admin.orgs_by_sy.index')) {
                $admin[] = $item('Organizations (by SY)', route('admin.orgs_by_sy.index'), ['admin.orgs_by_sy.*']);
            }

            if (Route::has('admin.audit-logs.index')) {
                $admin[] = $item('Audit Logs', route('admin.audit-logs.index'), ['admin.audit-logs.*']);
            }

            // ----- Groups -----
            if (!empty($home)) {
                $groups[] = ['key' => 'admin_home', 'title' => 'Home', 'links' => $home, 'icon' => 'home'];
            }

            if (!empty($rereg)) {
                $groups[] = ['key' => 'admin_rereg', 'title' => 'Re-Registration', 'links' => $rereg, 'icon' => 'clipboard'];
            }

            if (!empty($queues)) {
                $groups[] = ['key' => 'admin_queues', 'title' => 'Submission Queues', 'links' => $queues, 'icon' => 'inbox'];
            }

            if (!empty($orgTools)) {
                $groups[] = ['key' => 'admin_org_tools', 'title' => 'Org Tools', 'links' => $orgTools, 'icon' => 'grid'];
            }

            if (!empty($admin)) {
                $groups[] = ['key' => 'admin_admin', 'title' => 'Administration', 'links' => $admin, 'icon' => 'settings'];
            }
        }


        // =========================
        // ORG / MODERATOR
        // =========================
        if (!$isAdmin) {
            $activeOrgId = (int) session('active_org_id');

            $syId = (int) session('encode_sy_id');
            $activeSyId = (int) (SchoolYear::activeYear()?->id);

            if ($activeOrgId) {
                $orgRole = OrgMembership::query()
                    ->where('user_id', $user->id)
                    ->where('organization_id', $activeOrgId)
                    ->where('school_year_id', $syId)
                    ->whereNull('archived_at')
                    ->value('role');

                $isPresident = ($orgRole === 'president');

                if (!$isPresident) {
                    $osyPresident = OrganizationSchoolYear::query()
                        ->where('organization_id', $activeOrgId)
                        ->where('school_year_id', $syId)
                        ->value('president_user_id');

                    $isPresident = ((int)$osyPresident === (int)$user->id);
                }

                if ($isPresident) {
                    $rereg = [];
                    $ops   = [];

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
                        $ops[] = $item('Assign Treasurer/Moderator', route('org.assign-roles.edit'), ['org.assign-roles.*']);
                    }

                    if (Route::has('org.assign-project-heads.index')) {
                        $ops[] = $item('Assign Project Heads', route('org.assign-project-heads.index'), ['org.assign-project-heads.*']);
                    }

                    if (Route::has('org.activation-status.index')) {
                        $ops[] = $item('Activation Status', route('org.activation-status.index'), ['org.activation-status.*']);
                    }

                    if (!empty($rereg)) {
                        $groups[] = ['key' => 'org_rereg', 'title' => 'Re-Registration', 'links' => $rereg, 'icon' => 'clipboard'];
                    }

                    if (!empty($ops)) {
                        $groups[] = ['key' => 'org_ops', 'title' => 'Operational Modules', 'links' => $ops, 'icon' => 'grid'];
                    }
                }

                // =========================
                // PROJECT HEAD ACCESS
                // =========================

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

                if ($isModerator) {
                    $mod = [];

                    if (Route::has('org.moderator.rereg.dashboard')) {
                        $mod[] = $item('Re-Registration Dashboard', route('org.moderator.rereg.dashboard'), ['org.moderator.rereg.*']);
                    }

                    if (Route::has('org.moderator.strategic_plans.index')) {
                        $mod[] = $item('Review Strategic Plans (B-1)', route('org.moderator.strategic_plans.index'), ['org.moderator.strategic_plans.*']);
                    }



                    if (!empty($mod)) {
                        $groups[] = ['key' => 'org_mod', 'title' => 'Moderator', 'links' => $mod, 'icon' => 'user'];
                    }
                }
            }
        }
    }


    $groupShouldOpen = function (array $links): bool {
        foreach ($links as $l) {
            if (str_contains($l['class'], 'bg-blue-50')) return true;
        }
        return false;
        
    };

    $iconSvg = function (string $name) {
        return match ($name) {
            'clipboard' => '<svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6a2 2 0 012 2v14H7V7a2 2 0 012-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
            'grid' => '<svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h7v7H4V6zm9 0h7v7h-7V6zM4 15h7v7H4v-7zm9 0h7v7h-7v-7z"/></svg>',
            'settings' => '<svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.983 2.25a1 1 0 01.934.64l.53 1.35a8.46 8.46 0 012.06.858l1.44-.54a1 1 0 011.2.43l1.125 1.95a1 1 0 01-.23 1.26l-1.11.9c.1.67.1 1.36 0 2.03l1.11.9a1 1 0 01.23 1.26l-1.125 1.95a1 1 0 01-1.2.43l-1.44-.54a8.46 8.46 0 01-2.06.858l-.53 1.35a1 1 0 01-.934.64h-2.25a1 1 0 01-.934-.64l-.53-1.35a8.46 8.46 0 01-2.06-.858l-1.44.54a1 1 0 01-1.2-.43L2.1 15.77a1 1 0 01.23-1.26l1.11-.9a7.7 7.7 0 010-2.03l-1.11-.9a1 1 0 01-.23-1.26L3.225 7.47a1 1 0 011.2-.43l1.44.54a8.46 8.46 0 012.06-.858l.53-1.35a1 1 0 01.934-.64h2.25z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            'user' => '<svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14a4 4 0 10-8 0m8 0v1a3 3 0 01-3 3h-2a3 3 0 01-3-3v-1m6-7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            default => '<svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>',
        };
    };
@endphp

{{-- Dashboard (always) --}}
@include('layouts.nav._menu', ['links' => $dashboardLinks])

{{-- Context selector (org portal only, directly under dashboard) --}}
@if (!empty($contextLinks))
    @include('layouts.nav._menu', ['links' => $contextLinks])
@endif

{{-- Dropdown groups --}}
@foreach ($groups as $g)
    @php $open = $groupShouldOpen($g['links']); @endphp

    <div class="mt-3 rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <button
            type="button"
            class="w-full flex items-center justify-between px-4 py-3 hover:bg-slate-50 focus:outline-none"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('[data-chevron]').classList.toggle('rotate-180');"
            aria-expanded="{{ $open ? 'true' : 'false' }}"
        >
            <div class="flex items-center gap-2">
                {!! $iconSvg($g['icon'] ?? 'menu') !!}
                <span class="text-xs font-semibold tracking-wide text-slate-700 uppercase">
                    {{ $g['title'] }}
                </span>
            </div>

            <svg data-chevron class="h-4 w-4 text-slate-400 transition-transform {{ $open ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div class="{{ $open ? '' : 'hidden' }} border-t border-slate-200">
            <div class="p-2">
                @include('layouts.nav._menu', ['links' => $g['links']])
            </div>
        </div>
    </div>
@endforeach

@auth
    @include('layouts.nav._account', ['user' => $user, 'mode' => $mode ?? 'desktop'])
@endauth
