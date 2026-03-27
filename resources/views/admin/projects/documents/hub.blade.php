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




<a href="{{ route('admin.projects.documents.combined-proposal.open', $project) }}"
   class="block w-full text-left px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-sm font-medium transition">
      Pre-Implementation
</a>


<a
href="{{ route('admin.projects.packets.index', $project) }}"
class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700">

Submission Packets

</a>

</div>

</x-slot>


<div class="py-8">

<div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

{{-- CLEARANCE PANEL --}}
@include('admin.projects.documents.partials._clearance-panel')
@include('admin.projects.partials._clearance-actions')

{{-- DOCUMENT TABLE --}}
@include('admin.projects.documents.partials._documents-table')

@include('admin.projects.documents.partials._notices-table')




</div>

</div>

</x-app-layout>