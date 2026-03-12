<div class="border border-slate-300 border-t-0 px-4 py-4">

<div class="text-[12px] font-medium text-slate-700">
Complete List of Students Participating
</div>

<div class="mt-3 overflow-x-auto">

<table class="w-full border border-slate-300 text-[10px]">

<thead class="bg-slate-100">
<tr>
<th class="border px-2 py-1">Full Name</th>
<th class="border px-2 py-1">Course & Year</th>
<th class="border px-2 py-1">Student Mobile</th>
<th class="border px-2 py-1">Parent Name</th>
<th class="border px-2 py-1">Parent Mobile</th>
<th class="border px-2 py-1"></th>
</tr>
</thead>

<tbody id="participantsBody">

@if($participants->count())

@foreach($participants as $p)

<tr>

<td class="border px-2 py-1">
<input type="text"
       name="participants[{{ $loop->index }}][student_name]"
       value="{{ $p->student_name }}"
       class="w-full border-0 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>
</td>

<td class="border px-2 py-1">
<input type="text"
       name="participants[{{ $loop->index }}][course_year]"
       value="{{ $p->course_year }}"
       class="w-full border-0 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>
</td>

<td class="border px-2 py-1">
<input type="text"
       name="participants[{{ $loop->index }}][student_mobile]"
       value="{{ $p->student_mobile }}"
       class="w-full border-0 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>
</td>

<td class="border px-2 py-1">
<input type="text"
       name="participants[{{ $loop->index }}][parent_name]"
       value="{{ $p->parent_name }}"
       class="w-full border-0 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>
</td>

<td class="border px-2 py-1">
<input type="text"
       name="participants[{{ $loop->index }}][parent_mobile]"
       value="{{ $p->parent_mobile }}"
       class="w-full border-0 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>
</td>

<td class="border px-2 py-1 text-center">

@if(!$isReadOnly)

<button type="button"
        onclick="removeParticipant(this)"
        class="text-rose-600 text-[10px]">
Remove
</button>

@endif

</td>

</tr>

@endforeach

@else

{{-- Show one empty row if no participants yet --}}

<tr>

<td class="border px-2 py-1">
<input type="text"
       name="participants[0][student_name]"
       class="w-full border-0 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>
</td>

<td class="border px-2 py-1">
<input type="text"
       name="participants[0][course_year]"
       class="w-full border-0 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>
</td>

<td class="border px-2 py-1">
<input type="text"
       name="participants[0][student_mobile]"
       class="w-full border-0 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>
</td>

<td class="border px-2 py-1">
<input type="text"
       name="participants[0][parent_name]"
       class="w-full border-0 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>
</td>

<td class="border px-2 py-1">
<input type="text"
       name="participants[0][parent_mobile]"
       class="w-full border-0 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>
</td>

<td class="border px-2 py-1 text-center"></td>

</tr>

@endif

</tbody>

</table>

</div>

@if(!$isReadOnly)

<button type="button"
        onclick="addParticipant()"
        class="mt-2 px-3 py-1 bg-slate-200 text-[10px] rounded hover:bg-slate-300">
+ Add Participant
</button>

@endif

</div>