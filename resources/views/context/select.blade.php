<x-plain-layout>

@php

    $safeContexts = collect($contexts ?? [])
        ->filter(fn($context) => isset($context['organization']) && $context['organization'])
        ->values();

    $sortedContexts = $safeContexts
        ->sortBy(fn($context) => $context['organization']->id ?? 0)
        ->values();

    $orgCount = $sortedContexts->count();
@endphp

<div class="min-h-screen bg-gradient-to-b from-slate-100 via-white to-slate-50 px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto w-full max-w-6xl space-y-5">

        {{-- TOP BRAND CARD --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="bg-gradient-to-r from-blue-50 via-white to-slate-50 px-5 py-5 sm:px-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex min-w-0 items-center gap-3">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <img src="/images/sacdev-logo.jpg"
                                 alt="SACDEV Logo"
                                 class="h-full w-full object-cover">
                        </div>

                        <div class="min-w-0">
                            <div class="text-base font-semibold text-slate-900">
                                SACDEV System
                            </div>

                            <div class="mt-0.5 text-sm text-slate-500">
                                Select your organization and school year context to continue.
                            </div>
                        </div>

                    </div>

                    <button type="button"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-800 sm:w-auto">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                        Logout
                    </button>

                </div>
            </div>

        </div>

        {{-- MAIN CARD --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- HEADER --}}
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-5 sm:px-6">

                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    <div>
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                <i data-lucide="layers" class="h-4 w-4"></i>
                            </div>

                            <div>
                                <h1 class="text-base font-semibold text-slate-900">
                                    Select Working Context
                                </h1>

                                <p class="mt-0.5 text-sm text-slate-500">
                                    Your selected context controls the projects, documents, and roles visible in the system.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600">
                            <i data-lucide="building-2" class="h-3.5 w-3.5"></i>
                            {{ $orgCount }} {{ $orgCount === 1 ? 'Organization' : 'Organizations' }}
                        </span>

                        <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                            <i data-lucide="calendar-range" class="h-3.5 w-3.5"></i>
                            School Year Context
                        </span>
                    </div>

                </div>

            </div>

            {{-- ERRORS --}}
            @if ($errors->any())
                <div class="border-b border-rose-200 bg-rose-50 px-5 py-4 sm:px-6">
                    <div class="rounded-xl border border-rose-200 bg-white p-3 text-sm text-rose-800">
                        <div class="mb-1 font-semibold">Please fix the following:</div>

                        <ul class="list-disc space-y-0.5 pl-5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('context.update') }}">
                @csrf

                <input type="hidden" name="active_org_id">
                <input type="hidden" name="encode_sy_id">

                {{-- CONTEXT LIST --}}
                <div class="max-h-[70vh] overflow-y-auto px-5 py-5 sm:px-6">

                    <div class="space-y-5">

                        @forelse($sortedContexts as $context)

                            @php
                                $organization = $context['organization'];

                                $schoolYears = collect($context['school_years'] ?? [])
                                    ->filter(fn($sy) => isset($sy['id']))
                                    ->sortBy('id')
                                    ->values();

                                $isCurrentOrg = (int) ($activeOrgId ?? 0) === (int) $organization->id;

                                $logoPath = $organization->logo_path
                                    ?? $organization->logo
                                    ?? $organization->image_path
                                    ?? null;

                                $hasLogo = $logoPath
                                    && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath);

                            @endphp

                            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

                                {{-- ORG HEADER --}}
                                <div class="border-b border-slate-200 bg-white px-4 py-4 sm:px-5">

                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                                        <div class="flex min-w-0 items-center gap-3">

                                            @if($hasLogo)
                                                <div class="h-12 w-12 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                                    <img
                                                        src="{{ asset('storage/' . $logoPath) }}"
                                                        alt="{{ $organization->name }} Logo"
                                                        class="h-full w-full object-cover"
                                                    >
                                                </div>
                                            @else
                                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-500">
                                                    <i data-lucide="building-2" class="h-5 w-5"></i>
                                                </div>
                                            @endif

                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <h2 class="truncate text-sm font-semibold text-slate-900 sm:text-base">
                                                        {{ $organization->name }}
                                                    </h2>

                                                    @if($isCurrentOrg)
                                                        <span class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-700">
                                                            <i data-lucide="check" class="h-3 w-3"></i>
                                                            Current Org
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                                    <span>
                                                        Organization ID {{ $organization->id }}
                                                    </span>

                                                    <span class="text-slate-300">•</span>

                                                    <span>
                                                        {{ $schoolYears->count() }} {{ $schoolYears->count() === 1 ? 'school year' : 'school years' }} available
                                                    </span>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                {{-- SCHOOL YEAR CARDS --}}
                                <div class="p-4 sm:p-5">

                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">

                                        @forelse($schoolYears as $sy)

                                            @php
                                                $isSelected = (int) ($activeOrgId ?? 0) === (int) $organization->id
                                                    && (int) ($activeSyId ?? 0) === (int) $sy['id'];

                                                $isActiveSy = (bool) ($sy['is_active'] ?? false);

                                                $syLabel = $sy['label']
                                                    ?? $sy['name']
                                                    ?? 'School Year ' . $sy['id'];
                                            @endphp

                                            <button type="button"
                                                onclick="
                                                    const form = this.closest('form');
                                                    form.active_org_id.value='{{ $organization->id }}';
                                                    form.encode_sy_id.value='{{ $sy['id'] }}';
                                                    form.submit();
                                                "
                                                class="group relative w-full rounded-2xl border p-4 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md active:scale-[0.98]
                                                    {{ $isSelected
                                                        ? 'border-blue-400 bg-blue-50 ring-2 ring-blue-500/30'
                                                        : ($isActiveSy
                                                            ? 'border-emerald-300 bg-emerald-50 hover:bg-emerald-100'
                                                            : 'border-slate-200 bg-white hover:border-blue-200 hover:bg-blue-50/40')
                                                    }}"
                                            >

                                                <div class="flex items-start justify-between gap-3">

                                                    <div class="min-w-0">

                                                        <div class="flex flex-wrap items-center gap-2">

                                                            <span class="text-base font-semibold
                                                                {{ $isSelected
                                                                    ? 'text-blue-900'
                                                                    : ($isActiveSy ? 'text-emerald-900' : 'text-slate-900')
                                                                }}">
                                                                {{ $syLabel }}
                                                            </span>

                                                            @if($isSelected)
                                                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-700">
                                                                    Selected
                                                                </span>
                                                            @elseif($isActiveSy)
                                                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-emerald-700">
                                                                    Active
                                                                </span>
                                                            @endif

                                                        </div>

                                                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs
                                                            {{ $isSelected
                                                                ? 'text-blue-700'
                                                                : ($isActiveSy ? 'text-emerald-700' : 'text-slate-500')
                                                            }}">
                                                            <span>
                                                                School Year ID {{ $sy['id'] }}
                                                            </span>

                                                            @if($isActiveSy)
                                                                <span class="text-emerald-300">•</span>

                                                                <span class="inline-flex items-center gap-1">
                                                                    <i data-lucide="check-circle" class="h-3 w-3"></i>
                                                                    Active School Year
                                                                </span>
                                                            @endif
                                                        </div>

                                                    </div>

                                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border
                                                        {{ $isSelected
                                                            ? 'border-blue-200 bg-white text-blue-600'
                                                            : ($isActiveSy
                                                                ? 'border-emerald-200 bg-white text-emerald-600'
                                                                : 'border-slate-200 bg-slate-50 text-slate-400 group-hover:border-blue-200 group-hover:text-blue-600')
                                                        }}">
                                                        <i data-lucide="{{ $isSelected ? 'check-circle-2' : 'arrow-right' }}" class="h-4 w-4"></i>
                                                    </div>

                                                </div>

                                            </button>

                                        @empty

                                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-5 text-center sm:col-span-2 lg:col-span-3">
                                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400">
                                                    <i data-lucide="calendar-off" class="h-5 w-5"></i>
                                                </div>

                                                <div class="mt-2 text-sm font-semibold text-slate-700">
                                                    No school years available
                                                </div>

                                                <div class="mt-1 text-xs text-slate-500">
                                                    This organization exists, but you do not have any school year context available for it yet.
                                                </div>
                                            </div>

                                        @endforelse

                                    </div>

                                </div>

                            </section>

                        @empty

                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center sm:p-10">

                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400">
                                    <i data-lucide="building-x" class="h-7 w-7"></i>
                                </div>

                                <div class="mt-4 text-base font-semibold text-slate-800">
                                    You have no organization memberships yet
                                </div>

                                <div class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-500">
                                    Your account is currently not connected to any organization or school year context.
                                    Please contact your organization president or SACDEV administrator so they can add you as a member, officer, project head, moderator, or assigned project user.
                                </div>

                                <div class="mt-5">
                                    <button type="button"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-800">
                                        <i data-lucide="log-out" class="h-4 w-4"></i>
                                        Logout
                                    </button>
                                </div>

                            </div>

                        @endforelse

                    </div>

                </div>

            </form>

        </div>



    </div>

</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

</x-plain-layout>