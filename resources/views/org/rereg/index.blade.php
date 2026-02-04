<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900">
                Organization Re-Registration
            </h2>
            <div class="text-sm text-slate-600 mt-1">
                Select the target school year (SY) and complete required forms.
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                <div class="text-sm">{{ session('status') }}</div>
            </div>
        @endif

        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="POST" action="{{ route('org.rereg.setSy') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                @csrf

                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700">
                        Encode / Target School Year
                    </label>
                    <select name="encode_school_year_id"
                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-400 focus:outline-none"
                            required>
                        <option value="" disabled @selected(!$encodeSyId)>Select a school year...</option>
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy->id }}" @selected($encodeSyId && (int)$sy->id === (int)$encodeSyId)>
                                {{ $sy->name ?? $sy->label ?? ('SY #' . $sy->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Set SY
                </button>
            </form>
        </div>

        @if(!$encodeSyId)
            <div class="rounded-xl border border-slate-200 bg-white p-6 text-slate-700">
                Please select a target school year to see and fill out the required forms.
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach($forms as $key => $f)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-base font-semibold text-slate-900">{{ $f['label'] }}</div>
                                <div class="mt-1">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $f['badge']['class'] }}">
                                        {{ $f['badge']['text'] }}
                                    </span>
                                </div>
                            </div>

                            @if($f['editRoute'] && Route::has($f['editRoute']))
                                <a href="{{ route($f['editRoute']) }}"
                                   class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                    Open
                                </a>
                            @else
                                <span class="text-xs text-slate-500">No action</span>
                            @endif
                        </div>

                        @if($key === 'b5')
                            <div class="mt-4 text-sm text-slate-600">
                                This form is completed by the assigned moderator. You can track the status here.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Re-registration readiness</div>
                        <div class="text-sm text-slate-600 mt-1">
                            All required forms must be approved by SACDEV before activation.
                        </div>
                    </div>

                    @if($allApproved)
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-800">
                            Ready for Activation
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700">
                            Not Yet Complete
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
