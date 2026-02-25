<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

<form method="POST" action="{{ route('rereg.setSy') }}" class="flex gap-3 items-end">

@csrf

<div class="flex-1">

<label class="text-sm font-medium text-slate-700">
Target School Year
</label>

<select name="encode_school_year_id"
        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
        required>

<option disabled selected>
Select school year...
</option>

@foreach($schoolYears as $sy)

<option value="{{ $sy->id }}"
@selected($encodeSyId == $sy->id)>
{{ $sy->name }}
</option>

@endforeach

</select>

</div>


<button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">

Set School Year

</button>

</form>

</div>