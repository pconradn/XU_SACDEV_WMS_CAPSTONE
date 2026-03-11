<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
<div class="text-[12px] font-semibold text-slate-900 tracking-wide">
Ticket Selling Activity Information
</div>
</div>


<div class="px-4 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">

{{-- Activity Name --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Name of Activity where Ticket Selling was Conducted
</label>

<input
type="text"
name="activity_name"
value="{{ old('activity_name', $data->activity_name ?? $project->title) }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]"
@if($isReadOnly) disabled @endif>

</div>


{{-- Selling From --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Ticket Selling From
</label>

<input
type="date"
name="selling_from"
value="{{ old('selling_from', $data->selling_from ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]"
@if($isReadOnly) disabled @endif>

</div>


{{-- Selling To --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Ticket Selling To
</label>

<input
type="date"
name="selling_to"
value="{{ old('selling_to', $data->selling_to ?? '') }}"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]"
@if($isReadOnly) disabled @endif>

</div>


{{-- Total Ticket Sales --}}
<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Total Ticket Sales
</label>

<div
id="totalTicketSalesDisplay"
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px] bg-slate-50 font-semibold">
0.00
</div>

</div>

</div>

</div>