<x-app-layout>

<x-slot name="header">

<div class="flex items-center justify-between">

<div>
<h2 class="font-semibold text-xl text-slate-900 leading-tight">
{{ $organization->name }} — Projects
</h2>

<div class="text-sm text-slate-600 mt-1">
School Year: {{ $schoolYear->name }}
</div>
</div>

<a href="{{ route('admin.orgs_by_sy.show', [$organization->id, $schoolYear->id]) }}"
   class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
← Back to Org Profile
</a>

</div>

</x-slot>


<div class="py-8">

<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

<table class="min-w-full text-sm">

<thead class="bg-slate-50 border-b border-slate-200">

<tr class="text-left text-slate-700 font-semibold">
<th class="px-6 py-4">Project</th>
<th class="px-6 py-4 w-[240px]">Documents</th>
</tr>

</thead>

<tbody class="divide-y divide-slate-200">

@forelse ($projects as $p)

<tr class="hover:bg-slate-50 transition">

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

<td class="px-6 py-5">

<a href="{{ route('admin.projects.documents.hub', $p) }}"
   class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 transition">

View Documents

</a>

</td>

</tr>

@empty

<tr>

<td colspan="2"
    class="px-6 py-12 text-center text-slate-500">

No projects found for this organization and school year.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</div>

</x-app-layout>