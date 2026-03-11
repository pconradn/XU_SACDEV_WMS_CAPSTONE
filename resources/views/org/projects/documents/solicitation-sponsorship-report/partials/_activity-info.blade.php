<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
<div class="text-[12px] font-semibold text-slate-900 tracking-wide">
Solicitation Activity Information
</div>
</div>


<div class="px-4 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">


{{-- Activity Name --}}
<div class="md:col-span-2">

<label class="block text-[10px] font-medium text-blue-900 italic">
Name of Activity where Solicitation was Conducted
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
Purpose of Solicitation
</label>

<textarea
name="purpose"
rows="3"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">{{ old('purpose', $data->purpose ?? '') }}</textarea>

</div>


{{-- Solicitation From --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Solicitation From
</label>

<input
type="date"
name="solicitation_from"
value="{{ old('solicitation_from', $data->solicitation_from ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>


{{-- Solicitation To --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Solicitation To
</label>

<input
type="date"
name="solicitation_to"
value="{{ old('solicitation_to', $data->solicitation_to ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>


{{-- Letters Distributed --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Approved Number of Letters Distributed
</label>

<input
type="number"
name="approved_letters_distributed"
value="{{ old('approved_letters_distributed', $data->approved_letters_distributed ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">

</div>


{{-- Total Raised --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Total Amount Raised
</label>

<input
type="text"
id="totalAmountRaised"
readonly
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px] bg-slate-50">

</div>


</div>

</div>