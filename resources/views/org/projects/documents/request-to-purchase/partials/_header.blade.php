<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2">

<div class="text-[12px] font-semibold text-slate-900 tracking-wide">
Request to Purchase
</div>

</div>


<div class="px-4 py-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-[12px]">

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
Project Title
</label>

<input
type="text"
value="{{ $project->title }}"
disabled
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px] bg-slate-50">

</div>


<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
School Year
</label>

<input
type="text"
value="{{ $project->schoolYear->name ?? '' }}"
disabled
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px] bg-slate-50">

</div>


<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Project Head
</label>

<input
type="text"
value="{{ $project->projectHead?->user?->name ?? '' }}"
disabled
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px] bg-slate-50">

</div>


<div>

<label class="block text-[10px] font-medium text-blue-900 italic">
Application Date
</label>

<input
type="text"
value="{{ now()->format('F d, Y') }}"
disabled
class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px] bg-slate-50">

</div>


</div>

</div>