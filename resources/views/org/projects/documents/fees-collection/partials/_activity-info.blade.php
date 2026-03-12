<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
<div class="text-[12px] font-semibold text-slate-900 tracking-wide">
Collection Activity Information
</div>
</div>


<div class="px-4 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">

{{-- Activity Name --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Name of Activity where Collection was Done
</label>

<input
type="text"
name="activity_name"
value="{{ old('activity_name', $data->activity_name ?? $project->title) }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>


{{-- Purpose --}}
<div class="md:col-span-2">

<label class="block text-[10px] font-medium text-blue-900 italic">
Purpose of the Collection
</label>

<textarea
name="purpose"
rows="3"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">{{ old('purpose', $data->purpose ?? '') }}</textarea>

</div>


{{-- Collection From --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Collection From
</label>

<input
type="date"
name="collection_from"
value="{{ old('collection_from', $data->collection_from ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>


{{-- Collection To --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Collection To
</label>

<input
type="date"
name="collection_to"
value="{{ old('collection_to', $data->collection_to ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>

</div>

</div>