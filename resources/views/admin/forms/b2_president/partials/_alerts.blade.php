@if(session('success'))
    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-900">

        <div class="flex items-start gap-3">

            <svg class="w-5 h-5 mt-0.5 text-emerald-600 flex-shrink-0"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M5 13l4 4L19 7"/>
            </svg>

            <div>
                <div class="font-semibold">
                    Success
                </div>

                <div class="text-sm mt-1">
                    {{ session('success') }}
                </div>
            </div>

        </div>

    </div>
@endif



@if(session('error'))
    <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-900">

        <div class="flex items-start gap-3">

            <svg class="w-5 h-5 mt-0.5 text-rose-600 flex-shrink-0"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>

            <div>
                <div class="font-semibold">
                    Error
                </div>

                <div class="text-sm mt-1">
                    {{ session('error') }}
                </div>
            </div>

        </div>

    </div>
@endif



@if($errors->any())
    <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-900">

        <div class="flex items-start gap-3">

            <svg class="w-5 h-5 mt-0.5 text-rose-600 flex-shrink-0"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 9v2m0 4h.01M12 3C7.03 3 3 7.03 3 12s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9z"/>
            </svg>

            <div>

                <div class="font-semibold">
                    Please fix the following issues
                </div>

                <ul class="mt-2 text-sm list-disc pl-5 space-y-1">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        </div>

    </div>
@endif