<section class="space-y-3 sm:space-y-6">

    @isset($header)
        <div class="flex items-center justify-between px-2 sm:px-0">
            <div class="text-lg sm:text-xl font-semibold text-slate-800">
                {{ $header }}
            </div>
        </div>
    @endisset

    <div class="bg-white sm:rounded-2xl shadow-sm border border-slate-200">

        <div class="p-3 sm:p-6 lg:p-10">
            {{ $slot }}
        </div>

    </div>

</section>