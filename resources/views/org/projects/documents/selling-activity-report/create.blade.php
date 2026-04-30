<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">

@php
$docStatus = $document->status ?? 'draft';

$isProjectHead = $isProjectHead ?? false;

$isDraftee = \App\Models\ProjectAssignment::where('project_id', $project->id)
    ->where('user_id', auth()->id())
    ->where('assignment_role', 'draftee')
    ->whereNull('archived_at')
    ->exists();

$isEditable = (
    ($isProjectHead && (
        in_array($status, ['draft', 'submitted', 'returned'])
        || ($status === 'approved_by_sacdev' && $document->edit_mode)
    ))
    || ($isDraftee && $status === 'draft')
);

$isReadOnly = !$isEditable;

$currentApprover = $document?->signatures
    ?->where('status','pending')
    ->sortBy('id')
    ->first();
@endphp


@php
    $isAdminDocumentPage = auth()->user()?->system_role === 'sacdev_admin';

    $documentTitle = $document->formType?->name
        ?? $document->formType?->code
        ?? 'Document';
@endphp

<div class="bg-slate-50 pt-6">
    <div class="max-w-7xl mx-auto px-4">
        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-1.5">

                @if($isAdminDocumentPage)

                    <li>
                        <a href="{{ route('admin.orgs_by_sy.index') }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Organizations by School Year
                        </a>
                    </li>

                    <li class="text-slate-300">/</li>

                    <li>
                        <a href="{{ route('admin.orgs_by_sy.show', [$project->organization_id, $project->school_year_id]) }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            {{ $project->organization?->acronym ?: $project->organization?->name }}
                        </a>
                    </li>

                    <li class="text-slate-300">/</li>

                    <li>
                        <a href="{{ route('admin.org.projects.index', [$project->organization_id, $project->school_year_id]) }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Projects
                        </a>
                    </li>

                    <li class="text-slate-300">/</li>

                    <li>
                        <a href="{{ route('admin.projects.documents.hub', $project) }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Document Hub
                        </a>
                    </li>

                @else

                    <li>
                        <a href="{{ route('org.organization-info.show') }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Organization
                        </a>
                    </li>

                    <li class="text-slate-300">/</li>

                    <li>
                        <a href="{{ route('org.projects.index') }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Projects
                        </a>
                    </li>

                    <li class="text-slate-300">/</li>

                    <li>
                        <a href="{{ route('org.projects.documents.hub', $project) }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Document Hub
                        </a>
                    </li>

                @endif

                <li class="text-slate-300">/</li>

                <li class="font-medium text-indigo-700 truncate max-w-[220px]">
                    {{ $documentTitle }}
                </li>

            </ol>
        </nav>
    </div>
</div>


{{-- ================= STATUS BAR ================= --}}
@include('components.document.status-bar', ['document' => $document])


{{-- ================= HEADER ================= --}}
@include('org.projects.documents.selling-activity-report.partials._header')


{{-- ================= FORM ================= --}}
<form id="proposalForm"
      method="POST"
      action="{{ route('org.projects.documents.selling-activity-report.store', $project) }}">

@csrf
<input type="hidden" name="last_updated_at" value="{{ $document->updated_at }}">
<input type="hidden" name="action" id="formAction" value="draft">

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


<div class="space-y-6">


    @include('org.projects.documents.selling-activity-report.partials._activity-info')



    @include('org.projects.documents.selling-activity-report.partials._items-table')



</div>


@if($isReadOnly)
</fieldset>
@endif

</form>


{{-- ================= SIGNATURES ================= --}}
<div class="rounded-2xl border bg-white p-5 shadow-sm">
    @include('org.projects.documents.project-proposal.partials._signatures')
</div>


{{-- ================= ACTIONS ================= --}}
@include('components.project-document.actions._actions', [
    'project' => $project,
    'document' => $document,
    'currentSignature' => $document?->signatures
        ?->where('user_id', auth()->id())
        ->first(),
    'isProjectHead' => $isProjectHead ?? false,
    'isAdmin' => auth()->user()->system_role === 'sacdev_admin',
])


{{-- ================= SCRIPTS ================= --}}
@include('org.projects.documents.selling-activity-report.partials._scripts')


</div>

</x-app-layout>