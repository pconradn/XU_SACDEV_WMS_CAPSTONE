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
Solicitation Letter Draft (Google Docs / Word Link)
</label>

<div class="mt-2 border border-slate-300 rounded-lg bg-slate-50 p-4">

@if(!empty($data?->letter_draft_link))

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

<div class="text-[12px] text-slate-700">
Draft Link:
<span class="font-medium text-slate-900 break-all">
{{ $data->letter_draft_link }}
</span>
</div>

<div class="flex gap-2">

<a
href="{{ $data->letter_draft_link }}"
target="_blank"
class="px-3 py-1 text-[12px] bg-blue-600 text-white rounded hover:bg-blue-700">
Open Document
</a>

</div>

</div>

@endif


@if(!$isReadOnly)

<div class="mt-3">

<input
type="url"
name="letter_draft_link"
value="{{ old('letter_draft_link', $data->letter_draft_link ?? '') }}"
placeholder="Paste Google Docs or Microsoft Word link here"
class="w-full border border-slate-300 px-3 py-2 text-[12px]">

<div class="text-[11px] text-slate-500 mt-2 leading-relaxed">

Please upload your solicitation letter draft to:

• <span class="font-medium">Google Docs</span> or  
• <span class="font-medium">Microsoft Word Online</span>

Then paste the link above and ensure the document is shared as:

<span class="font-medium">Commenter</span>

This allows SACDEV to provide feedback directly on the letter without restarting the approval workflow.

</div>

</div>

@endif

</div>




@php
    $batch = $document?->solicitationBatches?->first();
@endphp

@if($batch)

<div class="md:col-span-2">

    <div class="border border-slate-300 rounded-lg bg-emerald-50 p-4">

        <div class="text-[11px] font-semibold uppercase tracking-wide text-emerald-800">
            SACDEV Approval Details
        </div>

        <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3 text-[12px]">

            <div>
                <div class="text-slate-500">
                    Approved Number of Letters
                </div>
                <div class="font-medium text-slate-900">
                    {{ $batch->approved_letter_count }}
                </div>
            </div>

            <div>
                <div class="text-slate-500">
                    Control Series Start
                </div>
                <div class="font-medium text-slate-900">
                    {{ $batch->control_series_start }}
                </div>
            </div>

            <div>
                <div class="text-slate-500">
                    Control Series End
                </div>
                <div class="font-medium text-slate-900">
                    {{ $batch->control_series_end }}
                </div>
            </div>

        </div>

    </div>

</div>

@endif

</div>

</div>

</div>