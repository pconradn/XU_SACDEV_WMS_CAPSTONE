<x-app-layout>

    @php
        $isOwner = auth()->id() === $user->id;
    @endphp

    <style>
        .page-container {
            max-width: 1200px;
        }
    </style>

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm px-5 py-4 flex items-center justify-between">

        <div class="flex items-start gap-3">
            <div class="mt-1 flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900">
                    {{ $isOwner ? 'My Profile' : 'Profile' }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $isOwner 
                        ? 'Manage your personal and organization-related information.' 
                        : 'Viewing user profile information.' 
                    }}
                </p>
            </div>
        </div>

    </div>


    <div class="py-8">
        <div class="page-container mx-auto px-4 lg:px-6 space-y-8">

            @if(session('success') && $isOwner)
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 shadow-sm">
                    <div class="text-xs font-medium text-emerald-800">
                        {{ session('success') }}
                    </div>
                </div>
            @endif


            @if($isOwner)
                <form method="POST" enctype="multipart/form-data" action="{{ route('org.profile.update') }}">
                    @csrf
            @endif


            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- PERSONAL --}}
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                        @include('org.profile.partials.personal', ['isOwner' => $isOwner])
                    </div>

                    {{-- CONTACT + ADDRESS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                            @include('org.profile.partials.contact', ['isOwner' => $isOwner])
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                            @include('org.profile.partials.address', ['isOwner' => $isOwner])
                        </div>

                    </div>

                    {{-- SKILLS --}}
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                        @include('org.profile.partials.skills', ['isOwner' => $isOwner])
                    </div>

                    {{-- MODERATOR --}}
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                        @if($user->isModerator())
                            @include('org.profile.partials.moderator', ['isOwner' => $isOwner])
                        @else
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/60 px-4 py-10 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                        <i data-lucide="user-x" class="w-4 h-4"></i>
                                    </div>
                                    <div class="text-xs font-medium text-slate-500">
                                        No moderator information
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>


                {{-- RIGHT COLUMN --}}
                <div class="space-y-6">

                    {{-- LEADERSHIPS --}}
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                        @include('org.profile.partials.leaderships', ['isOwner' => $isOwner])
                    </div>

                    {{-- TRAININGS --}}
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                        @include('org.profile.partials.trainings', ['isOwner' => $isOwner])
                    </div>

                    {{-- AWARDS --}}
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                        @include('org.profile.partials.awards', ['isOwner' => $isOwner])
                    </div>

                </div>

            </div>


            @if($isOwner)
                {{-- FLOATING ACTION BAR --}}
                <div class="fixed bottom-4 right-4 z-50">
                    <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-lg">

                        <span class="text-[11px] text-slate-500">
                            Unsaved changes
                        </span>

                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition">
                            Save Changes
                        </button>

                    </div>
                </div>

                </form>
            @endif

        </div>
    </div>

</x-app-layout>