<div class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">

<div class="mx-auto max-w-screen-2xl px-6 lg:px-8">

<div class="flex h-14 items-center justify-between gap-4">

{{-- LEFT LOGO --}}
<div class="flex items-center gap-3">

<div class="h-9 w-15 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold">
PWM
</div>

<div class="leading-tight">
<div class="text-sm font-semibold tracking-wide">
{{ config('app.name', 'SAcDev Workflow System') }}
</div>
<div class="text-xs text-slate-500">
Project Workflow Management
</div>
</div>

@auth
<span class="ml-2 text-[11px] px-2 py-1 rounded-full border
{{ $isAdmin ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">
{{ $isAdmin ? 'ADMIN PORTAL' : 'ORG PORTAL' }}
</span>
@endauth

</div>

{{-- SEARCH --}}
<div class="hidden md:flex flex-1 justify-center">
...
</div>

{{-- USER AREA --}}
<div class="flex items-center gap-3">
...
</div>

</div>
</div>
</div>