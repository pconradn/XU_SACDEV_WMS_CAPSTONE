<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">B-4 Members List</h2>
            <p class="mt-1 text-sm text-slate-600">
                Organization:
                <span class="font-semibold text-slate-900">{{ $list->organization->name ?? ('Org #' . $list->organization_id) }}</span>
                • Target SY:
                <span class="font-semibold text-slate-900">{{ $list->targetSchoolYear->label ?? $list->target_school_year_id }}</span>
            </p>
            <p class="mt-1 text-sm text-slate-600">
                Last updated:
                <span class="font-medium text-slate-900">{{ $list->updated_at?->format('M d, Y h:i A') ?? '—' }}</span>
            </p>
        </div>

        @include('admin.forms.b4_members.partials._members_table', ['items' => $list->items])

        <div class="mt-4 text-sm text-slate-600">
            View-only page. Any updates must be done by the organization president in the org portal.
        </div>
    </div>
</x-app-layout>
