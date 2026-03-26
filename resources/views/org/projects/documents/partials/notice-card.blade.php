<div class="rounded-xl border border-slate-200 p-4">

<div class="flex items-center justify-between mb-3">

<div class="font-semibold text-slate-800">
{{ $title }}
</div>

@if($createAllowed)

<a href="{{ $createRoute }}"
class="text-sm font-medium text-blue-600 hover:underline">
Create
</a>

@else

<span class="text-xs text-slate-400">
Cannot create
</span>

@endif

</div>


@if($items->count())

<div class="space-y-2">

@foreach($items as $doc)

<div class="flex items-center justify-between text-sm">

<div>

#{{ $loop->iteration }}

<span class="text-slate-500 ml-2">
{{ $doc->created_at->format('M d, Y') }}
</span>

</div>


<div class="flex items-center gap-3">

{{-- STATUS --}}
@if($doc->status === 'draft')

<span class="text-amber-600 font-medium">
Draft
</span>

@elseif($doc->status === 'submitted')

<span class="text-blue-600 font-medium">
Submitted
</span>

@elseif($doc->status === 'approved_by_sacdev')

<span class="text-emerald-600 font-medium">
Approved
</span>

@endif


{{-- ACTIONS --}}
@if($doc->status === 'draft')

<a href="{{ route(str_contains($title,'Postponement') ? 'org.projects.documents.postponement.edit' : 'org.projects.documents.cancellation.edit',[$project,$doc]) }}"
class="text-xs text-blue-600 hover:underline">
Edit
</a>


<form method="POST"
action="{{ route('org.projects.notices.archive',[$project,$doc]) }}"
onsubmit="return confirm('Remove this notice?')"
class="inline">

@csrf
@method('DELETE')

<button type="submit"
class="text-xs text-red-600 hover:underline">
Remove
</button>

</form>

@else

<a href="{{ route(str_contains($title,'Postponement') ? 'org.projects.documents.postponement.edit' : 'org.projects.documents.cancellation.edit',[$project,$doc]) }}"
class="text-xs text-slate-500 hover:underline">
View
</a>

@endif

</div>

</div>

@endforeach

</div>

@else

<div class="text-sm text-slate-500">
No notices created yet.
</div>

@endif

</div>