<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 py-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">B-2 President Registration</h2>
            <p class="mt-1 text-sm text-slate-600">
                Select the target School Year for the incoming president registration.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
                <div class="font-semibold">Success</div>
                <div class="text-sm mt-1">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                <div class="font-semibold">Error</div>
                <div class="text-sm mt-1">{{ session('error') }}</div>
            </div>
        @endif

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="POST" action="{{ route('org.b2.president.setTargetSy') }}">
                @csrf

                <label class="block text-sm font-medium text-slate-700">
                    Target School Year
                </label>

                <select name="target_school_year_id"
                        class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                        required>
                    @foreach($schoolYears as $sy)
                        <option value="{{ $sy->id }}" @selected($sy->id == $targetSyId)>
                            {{ $sy->label ?? $sy->name ?? ('SY #' . $sy->id) }}
                        </option>
                    @endforeach
                </select>

                <div class="mt-4">
                    <button type="submit"
                            class="inline-flex w-full justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 sm:w-auto">
                        Open Form
                    </button>
                </div>
            </form>
        </div>

        @if($registration)
            <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="text-sm text-slate-600">Current status</div>
                        <div class="text-base font-semibold text-slate-900">{{ $registration->status }}</div>
                    </div>

                    <a href="{{ route('org.b2.president.edit') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Continue
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
