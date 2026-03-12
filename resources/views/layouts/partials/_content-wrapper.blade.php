<section class="col-span-12 lg:col-span-9 xl:col-span-9">

@isset($header)
<div class="mb-4 rounded-2xl border border-slate-200 bg-white shadow-sm">
<div class="px-6 py-5">
{{ $header }}
</div>
</div>
@endisset

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
<div class="p-6">
{{ $slot }}
</div>
</div>

</section>