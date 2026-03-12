<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
            <h1 class="text-lg font-semibold text-slate-900">Select School Year</h1>
            <p class="text-sm text-slate-500 mt-1">
                Choose the school year this re-registration (Strategic Plan B-1) will be associated with.
            </p>

            @if(session('info'))
                <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-sm text-blue-700">
                    {{ session('info') }}
                </div>
            @endif

            @if($activeSy)
                <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                    Current Active School Year: <span class="font-semibold">{{ $activeSy->name }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('org.strategic_plan.select_sy.store') }}" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700">Target School Year</label>
                    <select name="target_school_year_id"
                            class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- select --</option>
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy->id }}"
                                @selected((int)old('target_school_year_id', $selectedId) === (int)$sy->id)>
                                {{ $sy->name }}
                                @if($activeSy && (int)$sy->id === (int)$activeSy->id) — (Active)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('target_school_year_id')
                        <div class="text-sm text-rose-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Continue
                    </button>
                    <p class="text-sm text-slate-500">
                        You can switch target school year anytime.
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
