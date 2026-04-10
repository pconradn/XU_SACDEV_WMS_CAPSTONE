<x-app-layout>

    @php
        $isOwner = auth()->id() === $user->id;
    @endphp

    <style>
        .page-container {
            max-width: 1200px;
        }

        .card {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background: linear-gradient(to bottom, #f8fafc, #ffffff);
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        .card-solid {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        .card-header {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #0f172a;
        }

        .muted {
            font-size: 0.75rem;
            color: #64748b;
        }
    </style>


    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm px-5 py-3 flex items-center justify-between">

        <div class="flex items-start gap-3">

            <div class="mt-1 flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>

            <div>
                <h2 class="text-base font-semibold text-slate-900">
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


    <div class="py-6">
        <div class="page-container mx-auto px-0 sm:px-4 lg:px-5 space-y-5">

            {{-- SUCCESS --}}
            @if(session('success') && $isOwner)
                <div class="card border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                    <div class="text-xs">{{ session('success') }}</div>
                </div>
            @endif




            @if($isOwner)
                <form method="POST" enctype="multipart/form-data" action="{{ route('org.profile.update') }}">
                    @csrf
            @endif

                <div class="space-y-5">

                    {{-- PERSONAL --}}
                    @include('org.profile.partials.personal', ['isOwner' => $isOwner])

                    {{-- CONTACT --}}
                    @include('org.profile.partials.contact', ['isOwner' => $isOwner])

                    {{-- ADDRESS --}}
                    @include('org.profile.partials.address', ['isOwner' => $isOwner])

                    {{-- MODERATOR --}}
                    @if($user->isModerator())
                        @include('org.profile.partials.moderator', ['isOwner' => $isOwner])
                    @endif

                    {{-- LEADERSHIPS --}}
                    @include('org.profile.partials.leaderships', ['isOwner' => $isOwner])

                    {{-- TRAININGS --}}
                    @include('org.profile.partials.trainings', ['isOwner' => $isOwner])

                    {{-- AWARDS --}}
                    @include('org.profile.partials.awards', ['isOwner' => $isOwner])

                    @include('org.profile.partials.skills', ['isOwner' => $isOwner])

                </div>

            @if($isOwner)
                {{-- ACTION BAR --}}
                <div class="flex justify-end mt-6">
                    <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 shadow-sm">

                        <span class="text-[11px] text-slate-500">
                            Save changes when done
                        </span>

                        <button type="submit"
                            class="px-4 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition">
                            Save Changes
                        </button>

                    </div>
                </div>

                </form>
            @endif

        </div>
    </div>

</x-app-layout>