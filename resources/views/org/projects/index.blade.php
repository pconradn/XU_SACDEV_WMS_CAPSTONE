<x-app-layout>

<x-slot name="header">
    @php
        $user = auth()->user();
        $orgRole = \App\Models\OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', session('active_org_id'))
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->value('role');

        $isPresident = $orgRole === 'president';
    @endphp

    <div class="flex items-center justify-between">

        <div>
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                Projects
            </h2>

            <div class="text-sm text-slate-600 mt-1">
                Encoding School Year ID: {{ $syId }}
            </div>
        </div>

        @if($isPresident)
            <a href="{{ route('org.projects.create') }}"
               class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800 transition">
                + Add Project
            </a>
        @endif

    </div>
</x-slot>



<div class="py-8">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
                {{ session('status') }}
            </div>
        @endif


        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-left text-slate-700 font-semibold">
                        <th class="px-6 py-4">Project</th>
                        <th class="px-6 py-4 w-[240px]">Documents</th>
                        @if($isPresident)
                            <th class="px-6 py-4 w-[200px] text-right">Management</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                @forelse ($projects as $p)

                    <tr class="hover:bg-slate-50 transition">

                        {{-- Project Info --}}
                        <td class="px-6 py-5">

                            <div class="font-semibold text-slate-900">
                                {{ $p->title }}
                            </div>

                            <div class="text-xs text-slate-500 mt-1">
                                @if($p->target_date)
                                    Target:
                                    {{ \Carbon\Carbon::parse($p->target_date)->format('M d, Y') }}
                                @else
                                    No target date set
                                @endif
                            </div>

                        </td>


                        {{-- Documents Button --}}
                        <td class="px-6 py-5">
                            <a href="{{ route('org.projects.documents.hub', $p) }}"
                               class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 transition">
                                Manage Documents
                            </a>
                        </td>


                        {{-- Management (President Only) --}}
                        @if($isPresident)
                        <td class="px-6 py-5 text-right">

                            <div class="flex justify-end gap-2">

                                <a href="{{ route('org.projects.edit', $p) }}"
                                   class="inline-flex items-center rounded-xl bg-amber-500 px-3 py-2 text-sm font-semibold text-white shadow hover:bg-amber-600 transition">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('org.projects.destroy', $p) }}"
                                      onsubmit="return confirm('Delete this project?');">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="inline-flex items-center rounded-xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white shadow hover:bg-rose-700 transition">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>
                        @endif

                    </tr>

                @empty

                    <tr>
                        <td colspan="{{ $isPresident ? 3 : 2 }}"
                            class="px-6 py-12 text-center text-slate-500">
                            No projects created yet.
                        </td>
                    </tr>

                @endforelse

                </tbody>
            </table>

        </div>

    </div>
</div>

</x-app-layout>