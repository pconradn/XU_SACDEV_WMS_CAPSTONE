<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">

        <div>
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                Projects
            </h2>

            <div class="text-sm text-slate-600 mt-1">
                Encoding School Year ID: {{ $syId }}
            </div>
        </div>

        <a href="{{ route('org.projects.create') }}"
           class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">

            + Add Project

        </a>

    </div>
</x-slot>



<div class="py-8">

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">


        {{-- Status message --}}
        @if (session('status'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif



        {{-- Projects Table --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr class="text-left text-slate-700 font-semibold">

                        <th class="px-5 py-3">
                            Project
                        </th>

                        <th class="px-5 py-3 w-[220px]">
                            Documents
                        </th>

                        <th class="px-5 py-3 w-[220px] text-right">
                            Management
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-200">

                @forelse ($projects as $p)

                    <tr class="hover:bg-slate-50 transition">


                        {{-- Project info --}}
                        <td class="px-5 py-4">

                            <div class="font-semibold text-slate-900">
                                {{ $p->title }}
                            </div>

                            <div class="text-xs text-slate-500 mt-1">

                                Category:
                                {{ $p->category ?? '—' }}

                                @if($p->target_date)
                                    • Target:
                                    {{ \Carbon\Carbon::parse($p->target_date)->format('M d, Y') }}
                                @endif

                            </div>

                        </td>



                        {{-- Documents Hub --}}
                        <td class="px-5 py-4">

                            <a href="{{ route('org.projects.documents.hub', $p) }}"
                               class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">

                                Open Documents Hub

                            </a>

                        </td>



                        {{-- Management --}}
                        <td class="px-5 py-4 text-right">

                            <div class="flex justify-end gap-2">


                                <a href="{{ route('org.projects.edit', $p) }}"
                                   class="inline-flex items-center rounded-lg bg-amber-500 px-3 py-2 text-sm font-semibold text-white hover:bg-amber-600">

                                    Edit

                                </a>


                                <form method="POST"
                                      action="{{ route('org.projects.destroy', $p) }}"
                                      onsubmit="return confirm('Delete this project?');">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="inline-flex items-center rounded-lg bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700">

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </td>


                    </tr>

                @empty

                    <tr>

                        <td colspan="3"
                            class="px-5 py-10 text-center text-slate-500">

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