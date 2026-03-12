@switch($status)

@case('draft')
<span class="px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs">
Draft
</span>
@break

@case('submitted')
<span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs">
Submitted
</span>
@break

@case('returned')
<span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">
Returned
</span>
@break

@case('approved_by_sacdev')
<span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">
Approved
</span>
@break

@default
<span class="text-xs text-slate-500">
—
</span>

@endswitch