<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2">

<div class="text-[12px] font-semibold text-slate-900 tracking-wide">
Activity Information
</div>

</div>


<div class="px-4 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">

<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Organization
</label>

<input
type="text"
value="{{ $project->organization->name }}"
disabled
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px] bg-slate-50">

</div>


<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Activity Name
</label>

<input
type="text"
name="activity_name"
value="{{ old('activity_name', $data->activity_name ?? $project->title) }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>


<div class="md:col-span-2">

<label class="block text-[10px] font-medium text-blue-900 italic">
Purpose of Solicitation
</label>

<textarea
name="purpose"
rows="3"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">{{ old('purpose', $data->purpose ?? '') }}</textarea>

</div>


<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Duration From
</label>

<input
type="date"
name="duration_from"
value="{{ old('duration_from', $data->duration_from ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>


<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Duration To
</label>

<input
type="date"
name="duration_to"
value="{{ old('duration_to', $data->duration_to ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>


<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Target Amount
</label>

<input
type="number"
name="target_amount"
value="{{ old('target_amount', $data->target_amount ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>


<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Desired Number of Letters
</label>

<input
type="number"
name="desired_letter_count"
value="{{ old('desired_letter_count', $data->desired_letter_count ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>


<div class="md:col-span-2">

<label class="block text-[10px] font-medium text-blue-900 italic">
Solicitation Letter Draft (PDF)
</label>

<div class="mt-2 border border-slate-300 rounded-lg bg-slate-50 p-4">

@if(!empty($data?->letter_draft_path))

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

<div class="text-[12px] text-slate-700">
Uploaded File:
<span class="font-medium text-slate-900">
{{ basename($data->letter_draft_path) }}
</span>
</div>

<div class="flex gap-2">

<a
href="{{ asset('storage/'.$data->letter_draft_path) }}"
target="_blank"
class="px-3 py-1 text-[12px] bg-blue-600 text-white rounded hover:bg-blue-700">
View
</a>

<a
href="{{ asset('storage/'.$data->letter_draft_path) }}"
download
class="px-3 py-1 text-[12px] border border-slate-400 text-slate-700 rounded hover:bg-slate-100">
Download
</a>

</div>

</div>

@endif


@if(!$isReadOnly)

<div class="mt-3">

<input
type="file"
name="letter_draft"
accept="application/pdf"
class="text-[12px]">

<div class="text-[11px] text-slate-500 mt-1">
Upload a PDF file containing the draft solicitation letter.
@if(!empty($data?->letter_draft_path))
Uploading a new file will replace the existing one.
@endif
</div>

</div>

@endif

</div>

</div>

</div>

</div>