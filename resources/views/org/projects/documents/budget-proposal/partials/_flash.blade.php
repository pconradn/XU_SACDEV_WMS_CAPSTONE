@if(session('success'))

<div class="mb-4 rounded border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
    {{ session('success') }}
</div>

@endif


@if(session('error'))

<div class="mb-4 rounded border border-rose-300 bg-rose-50 px-4 py-3 text-sm text-rose-800">
    {{ session('error') }}
</div>

@endif


@if($errors->any())

<div class="mb-4 rounded border border-rose-300 bg-rose-50 px-4 py-3 text-sm text-rose-800">

    <div class="font-semibold mb-1">
        Please fix the following errors:
    </div>

    <ul class="list-disc ml-5 space-y-1">

        @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif