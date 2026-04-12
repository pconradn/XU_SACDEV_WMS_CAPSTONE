<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">

        <div class="flex items-center gap-3">

            <div class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-600">
                <i data-lucide="files" class="w-4 h-4"></i>
            </div>

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Documents Overview
                </div>
                <div class="text-[11px] text-slate-500">
                    Organized by priority
                </div>
            </div>

        </div>

        <span class="text-[10px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
            {{ collect($documentsGrouped)->flatten()->count() }}
        </span>

    </div>


    {{-- SECTIONS --}}
    <div class="space-y-3 p-3">

        @if($documentsGrouped['action_required']->isNotEmpty())
            @include('admin.projects.documents.partials._documents-table-section', [
                'title' => 'Action Required',
                'color' => 'rose',
                'icon' => 'alert-circle',
                'items' => $documentsGrouped['action_required']
            ])
        @endif

        @if($documentsGrouped['required']->isNotEmpty())
            @include('admin.projects.documents.partials._documents-table-section', [
                'title' => 'Required Documents',
                'color' => 'amber',
                'icon' => 'clock',
                'items' => $documentsGrouped['required']
            ])
        @endif

        @if($documentsGrouped['submitted_optional']->isNotEmpty())
            @include('admin.projects.documents.partials._documents-table-section', [
                'title' => 'Submitted (Optional)',
                'color' => 'blue',
                'icon' => 'file-text',
                'items' => $documentsGrouped['submitted_optional']
            ])
        @endif

        @if($documentsGrouped['approved']->isNotEmpty())
            @include('admin.projects.documents.partials._documents-table-section', [
                'title' => 'Approved',
                'color' => 'emerald',
                'icon' => 'check-circle',
                'items' => $documentsGrouped['approved']
            ])
        @endif

        @if($documentsGrouped['others']->isNotEmpty())
            @include('admin.projects.documents.partials._documents-table-section', [
                'title' => 'Other Documents',
                'color' => 'slate',
                'icon' => 'folder',
                'items' => $documentsGrouped['others']
            ])
        @endif

    </div>

</div>