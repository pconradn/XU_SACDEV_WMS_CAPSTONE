<aside class="col-span-12 lg:col-span-3 xl:col-span-3">

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

<div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
<div class="text-xs font-semibold tracking-wide text-slate-700">
Navigation
</div>
<div class="text-[11px] text-slate-500">
Use the menu to access modules
</div>
</div>

<div class="p-2">
@include('layouts.navigation')
</div>

</div>

@auth
<div class="mt-4 rounded-2xl border border-slate-200 bg-white shadow-sm p-4">

<div class="text-xs font-semibold text-slate-700">Quick Info</div>

<div class="mt-2 space-y-2 text-sm">

<div class="flex items-center justify-between">
<span class="text-slate-500 text-xs">Portal</span>
<span class="text-xs font-semibold {{ $isAdmin ? 'text-blue-700' : 'text-emerald-700' }}">
{{ $isAdmin ? 'Admin' : 'Organization' }}
</span>
</div>

<div class="flex items-center justify-between">
<span class="text-slate-500 text-xs">Active SY</span>
<span class="text-xs font-semibold text-slate-800">
{{ $activeSy?->name ?? 'None' }}
</span>
</div>

</div>
</div>
@endauth

</aside>