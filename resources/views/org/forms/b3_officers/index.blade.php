<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 py-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">B-3 Officers List</h2>
            <p class="mt-1 text-sm text-slate-600">Select the target School Year to encode the officer list.</p>
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
            <form method="POST" action="{{ route('org.b3.officers-list.setTargetSy') }}">
                @csrf
                <label class="block text-sm font-medium text-slate-700">Target School Year</label>
                <select name="target_school_year_id"
                        class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                        required>
                    @foreach($schoolYears as $sy)
                        <option value="{{ $sy->id }}" @selected((int)$sy->id === (int)$targetSyId)>
                            {{ $sy->label ?? $sy->name ?? ('SY #' . $sy->id) }}
                        </option>
                    @endforeach
                </select>

                <div class="mt-4">
                    <button class="inline-flex w-full justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 sm:w-auto">
                        Open Form
                    </button>
                </div>
            </form>
        </div>

        @if($registration)
            <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-600">Current status</div>
                        <div class="font-semibold text-slate-900">{{ $registration->status }}</div>
                    </div>
                    <a href="{{ route('org.b3.officers-list.edit') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Continue
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
