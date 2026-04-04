<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-slate-800">
            Documents Overview
        </h2>

        <span class="text-[11px] text-slate-400">
            Organized by priority
        </span>
    </div>


    {{-- TABLE --}}
    <div class="divide-y">

        {{-- ACTION REQUIRED --}}
        @if($documentsGrouped['action_required']->isNotEmpty())
            @include('admin.projects.documents.partials._documents-table-section', [
                'title' => 'Action Required',
                'color' => 'rose',
                'icon' => 'alert-circle',
                'items' => $documentsGrouped['action_required']
            ])
        @endif


        {{-- REQUIRED --}}
        @if($documentsGrouped['required']->isNotEmpty())
            @include('admin.projects.documents.partials._documents-table-section', [
                'title' => 'Required Documents',
                'color' => 'amber',
                'icon' => 'clock',
                'items' => $documentsGrouped['required']
            ])
        @endif


        {{-- SUBMITTED OPTIONAL --}}
        @if($documentsGrouped['submitted_optional']->isNotEmpty())
            @include('admin.projects.documents.partials._documents-table-section', [
                'title' => 'Submitted (Optional)',
                'color' => 'blue',
                'icon' => 'file-text',
                'items' => $documentsGrouped['submitted_optional']
            ])
        @endif


        {{-- APPROVED --}}
        @if($documentsGrouped['approved']->isNotEmpty())
            @include('admin.projects.documents.partials._documents-table-section', [
                'title' => 'Approved',
                'color' => 'emerald',
                'icon' => 'check-circle',
                'items' => $documentsGrouped['approved']
            ])
        @endif


        {{-- OTHERS --}}
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