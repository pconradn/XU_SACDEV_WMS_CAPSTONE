<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-lg font-semibold text-slate-900">
            SACDEV Clearance Check
        </h1>
        <p class="text-sm text-slate-500">
            Enter a student ID to check clearance status and project responsibilities.
        </p>
    </div>

    {{-- SEARCH CARD --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">

        <form method="POST" action="{{ route('clearance.search') }}" class="flex flex-col sm:flex-row gap-3">
            @csrf

            <input
                type="text"
                name="student_id"
                value="{{ old('student_id', $searched_id ?? '') }}"
                placeholder="Enter Student ID (e.g. 20201234)"
                class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                required
            >

            <button class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                Check
            </button>
        </form>

        @if(session('error'))
            <div class="mt-4 text-sm text-rose-600 font-medium">
                {{ session('error') }}
            </div>
        @endif

    </div>

    {{-- RESULT --}}
    @isset($user)

        {{-- STATUS CARD --}}
        <div class="rounded-2xl border shadow-sm p-6
            {{ $isCleared
                ? 'border-emerald-200 bg-emerald-50'
                : 'border-rose-200 bg-rose-50'
            }}
        ">

            <div class="flex items-center justify-between">

                <div>
                    <div class="text-xs uppercase tracking-wide
                        {{ $isCleared ? 'text-emerald-700' : 'text-rose-700' }}">
                        Clearance Status
                    </div>

                    <div class="mt-1 text-lg font-semibold
                        {{ $isCleared ? 'text-emerald-900' : 'text-rose-900' }}">
                        {{ $isCleared ? 'CLEARED FOR SACDEV' : 'NOT CLEARED' }}
                    </div>

                    <div class="text-sm mt-1 text-slate-600">
                        {{ $user->name }} ({{ $user->email }})
                    </div>
                </div>

                @if(!$isCleared)
                    <div class="text-sm font-semibold text-rose-700">
                        {{ $blockingProjects->count() }} Pending
                    </div>
                @endif

            </div>
        </div>

        {{-- PROJECT TABLE --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b">
                <h2 class="text-sm font-semibold text-slate-900">
                    Project Responsibilities
                </h2>
            </div>

            @if($projects->isEmpty())
                <div class="p-6 text-sm text-slate-500">
                    No project assignments found.
                </div>
            @else

                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm">

                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                            <tr>
                                <th class="px-6 py-3 text-left">Project</th>
                                <th class="px-6 py-3 text-left">Role</th>
                                <th class="px-6 py-3 text-left">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">

                            @foreach($projects as $project)

                                @php
                                    $status = $project->workflow_status;

                                    $statusStyles = [
                                        'planning' => 'bg-slate-50 text-slate-700',
                                        'drafting' => 'bg-slate-50 text-slate-700',
                                        'submitted' => 'bg-blue-50 text-blue-700',
                                        'under_review' => 'bg-amber-50 text-amber-700',
                                        'returned' => 'bg-rose-50 text-rose-700',
                                        'approved' => 'bg-emerald-50 text-emerald-700',
                                        'post_implementation' => 'bg-indigo-50 text-indigo-700',
                                        'completed' => 'bg-emerald-100 text-emerald-800',
                                        'cancelled' => 'bg-slate-100 text-slate-600',
                                    ];

                                    $style = $statusStyles[$status] ?? 'bg-slate-50 text-slate-700';
                                @endphp

                                <tr class="hover:bg-slate-50">

                                    {{-- PROJECT --}}
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">
                                            {{ $project->title }}
                                        </div>
                                    </td>

                                    {{-- ROLE --}}
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $project->assignment_role }}
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $style }}">
                                            {{ str_replace('_',' ', $status) }}
                                        </span>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    @endisset

</div>

</x-app-layout>