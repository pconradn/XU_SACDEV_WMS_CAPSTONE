<x-app-layout>

@php
    $isAdminReregHub = $isAdminReregHub ?? false;
    $user = auth()->user();
@endphp

<div class="mx-auto max-w-6xl px-4 py-6 space-y-6">

    
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

            <div class="space-y-1">
                <h2 class="text-lg font-semibold text-slate-900">
                    Organization Re-Registration
                </h2>

                @php
                    $roleText = match (true) {
                        $user?->isSacdev() => 'Review submitted forms and monitor organization registration progress.',
                        $isPresident => 'Complete and oversee all required forms for your organization.',
                        $isModerator => 'Review and complete moderator requirements as part of the registration process.',
                        default => 'View registration progress.',
                    };
                @endphp

                <p class="text-xs text-slate-500">
                    {{ $roleText }}
                </p>
            </div>

            <div class="flex items-center gap-2">

  
                <button
                    x-data
                    @click="$dispatch('open-guide-modal')"
                    class="relative inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 shadow-sm hover:bg-blue-100 transition">

         
                    <span class="absolute -inset-1 rounded-xl bg-blue-300 opacity-40 animate-ping"></span>

                    {{-- CONTENT --}}
                    <span class="relative flex items-center gap-2">
                        <i data-lucide="book-open" class="h-3.5 w-3.5"></i>
                        Guide
                    </span>
                </button>

            
                @if($isActivated)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                        <i data-lucide="check-circle-2" class="h-3.5 w-3.5"></i>
                        Registered
                    </span>
                @elseif($allApproved)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                        <i data-lucide="sparkles" class="h-3.5 w-3.5"></i>
                        Ready
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                        <i data-lucide="clock-3" class="h-3.5 w-3.5"></i>
                        In Progress
                    </span>
                @endif

            </div>

        </div>
    </div>

   
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

        <div class="flex items-start gap-3">

            <div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600">
                <i data-lucide="user" class="h-4 w-4"></i>
            </div>

            <div class="flex-1 space-y-2">

                <div class="text-xs font-semibold text-slate-900">
                    Your Role
                </div>

                @if($user?->isSacdev())
                    <div class="text-xs text-slate-600">
                        You are viewing this as an <span class="font-medium text-slate-800">administrator</span>. You can review submissions and track organization readiness.
                    </div>

                @elseif($isPresident)
                    <div class="text-xs text-slate-600">
                        You are the <span class="font-medium text-slate-800">organization president</span>. You are responsible for completing and ensuring all required forms are submitted.
                    </div>

                @elseif($isModerator)
                    <div class="text-xs text-slate-600">
                        You are assigned as <span class="font-medium text-slate-800">moderator</span>. Complete your profile and review responsibilities.
                    </div>

                @else
                    <div class="text-xs text-slate-600">
                        You are a <span class="font-medium text-slate-800">member</span>. Monitor re-registration progress.
                    </div>
                @endif

            </div>

        </div>
    </div>


    {{-- ================= STATUS MESSAGES ================= --}}
    @if(session('error'))
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('status'))
        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
            {{ session('status') }}
        </div>
    @endif


    {{-- ================= NO SCHOOL YEAR ================= --}}
    @if(!$encodeSyId)

        <div class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-600">
            Please select a target school year to continue.
        </div>

    @else

        {{-- ================= ACTIVATED STATE ================= --}}
        @if($isActivated)
            <div class="rounded-2xl border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white p-5 shadow-sm flex justify-between items-center">

                <div>
                    <div class="text-sm font-semibold text-emerald-900">
                        Organization Already Registered
                    </div>
                    <div class="text-xs text-emerald-700 mt-1">
                        This organization is already active for the selected school year.
                    </div>
                </div>

                <span class="text-xs font-semibold bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full">
                    Registered
                </span>

            </div>
        @endif


        {{-- ================= FORMS GRID ================= --}}
        @include('org.rereg.partials._forms-grid', [
            'forms' => $forms,
            'presidentUser' => $presidentUser,
            'isPresidentProfileComplete' => $isPresidentProfileComplete,
            'b5Moderator' => $b5Moderator,
            'isModerator' => $isModerator,
            'canAssignModerator' => $canAssignModerator,
            'constitutionSubmission' => $constitutionSubmission,
            'encodeSyId' => $encodeSyId,
            'isAdminReregHub' => $isAdminReregHub,
            'isPresident' => $isPresident,
        ])


        <div class="sticky bottom-4 z-40">

            <div class="mx-auto max-w-6xl px-2">

                <div class="rounded-2xl border border-slate-200 bg-white/90 backdrop-blur
                            shadow-lg p-4">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div class="text-xs text-slate-600">
                            @if($isActivated)
                                Registration complete for this school year.
                            @elseif($user?->isSacdev())
                                Monitor submissions and approve completed requirements.
                            @elseif($isPresident)
                                Ensure all required forms are completed and submitted.
                            @elseif($isModerator)
                                Complete your assigned moderator requirements.
                            @else
                                Await progress updates from your organization officers.
                            @endif
                        </div>

                        <div class="flex items-center gap-2">

                            @if($isActivated)

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                    Registered
                                </span>

                            @elseif($allApproved && $user?->isSacdev())

                                <button
                                    x-data
                                    @click="$dispatch('open-activate-modal')"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold
                                        bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm">

                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                    Register Organization
                                </button>

                            @else

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                    Incomplete
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>


@include('org.rereg.partials._guide-modal')

@if($user?->isSacdev())
<div 
    x-data="{ open: false }"
    x-on:open-activate-modal.window="open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
>

    <div @click.away="open = false"
         class="w-full max-w-md rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-xl p-5">

        <div class="flex items-start gap-3">

            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
            </div>

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Confirm Registration
                </div>

                <div class="text-xs text-slate-500 mt-0.5">
                    Register organization for selected school year
                </div>
            </div>

        </div>

        <div class="mt-4 text-sm text-slate-700">
            Are you sure you want to register
            <span class="font-semibold text-slate-900">
                {{ $organization->name }}
            </span>
            for the selected school year?
        </div>

        <div class="mt-5 flex justify-end gap-2">

            <button type="button"
                @click="open = false"
                class="px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                Cancel
            </button>

            <form method="POST" action="{{ route('admin.rereg.activate', $organization) }}">
                @csrf
                <button type="submit"
                    class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition">
                    Confirm
                </button>
            </form>

        </div>

    </div>
</div>
@endif

</x-app-layout>