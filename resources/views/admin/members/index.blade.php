<x-app-layout>
    <div class="mx-auto flex max-w-7xl flex-col gap-5 px-4 py-6">
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                        <i data-lucide="users" class="h-3.5 w-3.5"></i>
                        Members Directory
                    </div>

                    <h1 class="text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">
                        Organization Members
                    </h1>

                    <p class="max-w-2xl text-xs text-slate-500 sm:text-sm">
                        View member records, academic details, and contact information in one organized directory.
                    </p>
                </div>

                <form method="GET" class="w-full lg:w-auto">
                    <input type="hidden" name="organization_id" value="{{ $orgId }}">
                    <input type="hidden" name="school_year_id" value="{{ $syId }}">

                    <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative w-full sm:w-80">
                            <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Search members..."
                                class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-xs text-slate-700 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-slate-300 focus:ring-2 focus:ring-slate-200 sm:text-sm"
                            >
                        </div>

                        <button
                            type="submit"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 sm:text-sm">
                            <i data-lucide="filter" class="h-4 w-4 text-slate-500"></i>
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                            Total Results
                        </div>
                        <div class="mt-1 text-2xl font-semibold text-slate-900">
                            {{ $members->total() }}
                        </div>
                    </div>
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-2 text-blue-700">
                        <i data-lucide="users" class="h-4 w-4"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                            Current Page
                        </div>
                        <div class="mt-1 text-2xl font-semibold text-slate-900">
                            {{ $members->currentPage() }}
                        </div>
                    </div>
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-2 text-amber-700">
                        <i data-lucide="file-text" class="h-4 w-4"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                            Showing
                        </div>
                        <div class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $members->firstItem() ?? 0 }} - {{ $members->lastItem() ?? 0 }}
                        </div>
                    </div>
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-2 text-emerald-700">
                        <i data-lucide="list" class="h-4 w-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white px-4 py-4 sm:px-5">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">
                            Member List
                        </h2>
                        <p class="text-xs text-slate-500">
                            Directory of registered organization members
                        </p>
                    </div>

                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-[11px] font-medium text-slate-500">
                        <i data-lucide="shield-check" class="h-3.5 w-3.5 text-slate-400"></i>
                        Read-only admin view
                    </div>
                </div>
            </div>

            <div class="max-h-[560px] overflow-x-auto overflow-y-auto">
                <table class="min-w-full text-xs sm:text-sm">
                    <thead class="sticky top-0 z-10 border-b border-slate-200 bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Member</th>
                            <th class="px-4 py-3 text-left font-semibold">Student ID</th>
                            <th class="px-4 py-3 text-left font-semibold">Course</th>
                            <th class="px-4 py-3 text-left font-semibold">QPI</th>
                            <th class="px-4 py-3 text-left font-semibold">Contact</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($members as $m)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-4 py-3 align-top">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500">
                                            <i data-lucide="user" class="h-4 w-4"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="truncate font-semibold text-slate-900">
                                                {{ trim(($m->last_name ?? '') . ', ' . ($m->first_name ?? '') . ' ' . ($m->middle_initial ?? '')) ?: '-' }}
                                            </div>
                                            <div class="mt-1 break-all text-[11px] text-slate-500">
                                                {{ $m->email ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3 align-top text-slate-600">
                                    <span class="inline-flex rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-700">
                                        {{ $m->student_id_number ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 align-top text-slate-600">
                                    <div class="max-w-[220px] leading-relaxed">
                                        {{ $m->course_and_year ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-4 py-3 align-top">
                                    <span class="inline-flex rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                        {{ $m->latest_qpi ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 align-top text-[11px] text-slate-500 sm:text-xs">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="phone" class="h-3.5 w-3.5 text-slate-400"></i>
                                        <span>{{ $m->mobile_number ?? '-' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-14 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 text-amber-700">
                                            <i data-lucide="search-x" class="h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-slate-900">
                                                No members found
                                            </div>
                                            <p class="mt-1 text-xs text-slate-500">
                                                No matching records are available for the current filters.
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                {{ $members->links() }}
            </div>
        </div>
    </div>
</x-app-layout>