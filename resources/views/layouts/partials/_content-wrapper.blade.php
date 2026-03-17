<section class="space-y-6">

@isset($header)

<div class="flex items-center justify-between">

<div class="text-xl font-semibold text-slate-800">
{{ $header }}
</div>

</div>

@endisset



<div class="bg-white rounded-2xl shadow-sm border border-slate-200">

<div class="p-8">

{{ $slot }}

</div>

</div>

</section>