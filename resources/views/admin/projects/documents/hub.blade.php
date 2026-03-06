<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-xl text-slate-900">
                    {{ $project->title }}
                </h2>

                <div class="text-sm text-slate-600 mt-1">
                    Project Documents — SACDEV Review
                </div>

            </div>

            <a href="{{ route('admin.org.projects.index', [$project->organization_id, $project->school_year_id]) }}"
               class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">

                ← Back to Projects

            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <table class="min-w-full text-sm">

                    <thead class="bg-slate-50 border-b border-slate-200">

                        <tr class="text-left text-slate-700 font-semibold">

                            <th class="px-6 py-4">
                                Document
                            </th>

                            <th class="px-6 py-4 w-[160px]">
                                Status
                            </th>

                            <th class="px-6 py-4 w-[220px] text-right">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-200">


                        @php

                            $types = [
                                'PROJECT_PROPOSAL'       => 'Project Proposal',
                                'BUDGET_PROPOSAL'        => 'Budget Proposal',
                                'OFF_CAMPUS_APPLICATION' => 'Off-Campus Form',
                            ];

                            $formRoutes = [
                                'PROJECT_PROPOSAL'       => 'org.projects.project-proposal.create',
                                'BUDGET_PROPOSAL'        => 'org.projects.budget-proposal.create',
                                'OFF_CAMPUS_APPLICATION' => 'org.projects.off-campus.create',
                            ];

                        @endphp


                        @foreach($types as $code => $label)

                            @php
                                $doc = $documents[$code] ?? null;
                                $status = $doc->status ?? 'not_created';
                                $routeName = $formRoutes[$code] ?? null;
                            @endphp


                            <tr>

                                <td class="px-6 py-5">

                                    <div class="font-semibold text-slate-900">
                                        {{ $label }}
                                    </div>

                                    @if(!$doc)

                                        <div class="text-xs text-slate-500 mt-1">
                                            Not created yet
                                        </div>

                                    @endif

                                </td>


                                <td class="px-6 py-5">

                                    @switch($status)

                                        @case('draft')
                                            <span class="px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs">
                                                Draft
                                            </span>
                                        @break

                                        @case('submitted')
                                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs">
                                                Submitted
                                            </span>
                                        @break

                                        @case('returned')
                                            <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">
                                                Returned
                                            </span>
                                        @break

                                        @case('approved')
                                            <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">
                                                Approved
                                            </span>
                                        @break

                                        @default
                                            <span class="text-xs text-slate-500">
                                                —
                                            </span>

                                    @endswitch

                                </td>


                                <td class="px-6 py-5 text-right">

                                    @if($doc && $routeName)

                                        <a href="{{ route('admin.projects.documents.open', [$project, $code]) }}"
                                           class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 transition">

                                            Open

                                        </a>

                                    @endif

                                </td>


                            </tr>

                        @endforeach


                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>