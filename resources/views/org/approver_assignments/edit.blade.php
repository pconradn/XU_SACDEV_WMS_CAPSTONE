<x-app-layout>

<div class="max-w-4xl mx-auto px-5 py-6 space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>

            <div>
                <h1 class="text-sm font-semibold text-slate-900">
                    Assign Approvers
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Assign Treasurer and Finance Officer before proceeding
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('org.approver-assignments.update') }}" class="space-y-4">
        @csrf

        @php
            $roles = [
                'treasurer' => 'Treasurer',
                'finance_officer' => 'Budget and Finance Officer',
            ];
        @endphp

        @foreach($roles as $key => $label)

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">

                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="user-check" class="w-4 h-4 text-slate-500"></i>
                    <span class="text-xs font-semibold text-slate-700">
                        {{ $label }}
                    </span>
                </div>

                <select name="{{ $key }}_id"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">Select officer...</option>

                    @foreach($officers as $officer)
                        <option value="{{ $officer->id }}"
                            @if(optional($current[$key])->id === $officer->id) selected @endif>
                            {{ $officer->display_name }}
                            ({{ $officer->officerEntry?->position ?? 'No position' }})
                        </option>
                    @endforeach
                </select>

            </div>

        @endforeach

        <div class="flex justify-end pt-2">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition shadow-sm">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Assignments
            </button>
        </div>

    </form>

</div>

</x-app-layout>