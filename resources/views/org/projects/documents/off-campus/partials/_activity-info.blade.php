<div class="border border-slate-300 border-t-0">

{{-- Organization --}}
<div class="px-4 pt-4 pb-3 border-b border-slate-300">

<label class="block text-[10px] font-medium text-blue-900 italic">
Name of Organization
</label>

{{-- Hidden input so it saves to DB --}}
<input type="hidden"
       name="organization_name"
       value="{{ $project->organization->name }}">

<input type="text"
       value="{{ $project->organization->name }}"
       readonly
       class="mt-1 w-full border border-slate-300 bg-slate-100 px-3 py-1 text-[10px]">

</div>


{{-- Activity Name --}}
<div class="px-4 pt-4 pb-3 border-b border-slate-300">

<label class="block text-[10px] font-medium text-blue-900 italic">
Name of Activity
</label>

<input type="text"
       name="activity_name"
       value="{{ old('activity_name', optional($activity)->activity_name ?? $project->title) }}"
       class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>

</div>


{{-- Inclusive Dates --}}
<div class="px-4 pt-4 pb-3 border-b border-slate-300">

<label class="block text-[10px] font-medium text-blue-900 italic">
Inclusive Date(s) of Activity
</label>

<input type="text"
       name="inclusive_dates"
       value="{{ old('inclusive_dates', optional($activity)->inclusive_dates) }}"
       placeholder="Example: March 15–17, 2026"
       class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>

</div>


{{-- Venue --}}
<div class="px-4 pt-4 pb-3">

<label class="block text-[10px] font-medium text-blue-900 italic">
Venue / Destination
</label>

<input type="text"
       name="venue_destination"
       value="{{ old('venue_destination', optional($activity)->venue_destination) }}"
       class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[10px]"
       {{ $isReadOnly ? 'readonly' : '' }}>

</div>

</div>