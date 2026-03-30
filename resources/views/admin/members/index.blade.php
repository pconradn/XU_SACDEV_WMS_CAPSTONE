<x-app-layout>

<div class="max-w-7xl mx-auto px-4 py-6 space-y-5">

    @php
        $backUrl = $orgId
            ? route('admin.orgs_by_sy.show', $orgId)
            : route('admin.orgs_by_sy.index');
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

        <div>
            <h1 class="text-xl font-semibold text-slate-900">
                Members Directory
            </h1>
            <p class="text-sm text-slate-500">
                Overview of organization members
            </p>
        </div>

        <div class="flex items-center gap-2">

            {{-- SEARCH FORM --}}
            <form method="GET" class="flex items-center gap-2">

                <input type="hidden" name="organization_id" value="{{ $orgId }}">
                <input type="hidden" name="school_year_id" value="{{ $syId }}">

                <div class="relative">
                    <input type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search members..."
                        class="pl-9 pr-3 h-9 text-sm border border-slate-200 rounded-xl 
                               focus:ring-2 focus:ring-slate-200 focus:outline-none">

                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"
                        fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                    </svg>
                </div>

            </form>

            {{-- BACK --}}
            <a href="{{ $backUrl }}"
               class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 hover:bg-slate-50">
                ← Back
            </a>

        </div>

    </div>


    {{-- CARD --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

            <table class="min-w-full text-sm">

                {{-- HEAD --}}
                <thead class="sticky top-0 bg-slate-50 z-10 border-b text-xs uppercase text-slate-500">
                    <tr>
                        <th class="py-3 px-4 text-left">Member</th>
                        <th class="py-3 px-4 text-left">Student ID</th>
                        <th class="py-3 px-4 text-left">Course</th>
                        <th class="py-3 px-4 text-left">QPI</th>
                        <th class="py-3 px-4 text-left">Contact</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y">

                @forelse ($members as $m)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="py-3 px-4">
                            <div class="font-medium text-slate-900">
                                {{ $m->last_name }}, {{ $m->first_name }} {{ $m->middle_initial }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $m->email ?? '-' }}
                            </div>
                        </td>

                        <td class="py-3 px-4 text-slate-600">
                            {{ $m->student_id_number ?? '-' }}
                        </td>

                        <td class="py-3 px-4 text-slate-600">
                            {{ $m->course_and_year ?? '-' }}
                        </td>

                        <td class="py-3 px-4">
                            <span class="text-sm font-semibold text-slate-800">
                                {{ $m->latest_qpi ?? '-' }}
                            </span>
                        </td>

                        <td class="py-3 px-4 text-xs text-slate-500">
                            {{ $m->mobile_number ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400">
                            No members found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="px-4 py-3 border-t bg-slate-50">
            {{ $members->links() }}
        </div>

    </div>

</div>

</x-app-layout>