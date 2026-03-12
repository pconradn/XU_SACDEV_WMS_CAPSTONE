@if(!empty($isActivated))

<div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-5">

    <div class="flex items-center justify-between">

        <div>
            <div class="font-semibold text-emerald-900">
                Organization already registered
            </div>

            <div class="text-sm text-emerald-800">
                All forms are now read-only.
            </div>
        </div>

        <span class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-800">

            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>

            Registered

        </span>

    </div>

</div>

@endif