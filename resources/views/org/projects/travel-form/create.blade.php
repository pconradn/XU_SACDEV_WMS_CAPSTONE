<x-app-layout>

<div class="max-w-5xl mx-auto py-8">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-slate-900">
            Generate Student Travel Form
        </h1>
        <p class="text-sm text-slate-500">
            Fill in the details below. This will generate a printable consent form.
        </p>
    </div>

    <form method="POST" action="{{ route('org.projects.documents.off-campus.travel-form.generate', $project->id) }}">
        @csrf

        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6 space-y-6">

            {{-- ACTIVITY --}}
            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <label class="text-sm font-medium text-slate-700">Name of Activity</label>
                    <input type="text" name="activity_name"
                        value="{{ old('activity_name', $project->name) }}"
                        class="mt-1 w-full rounded-lg border-slate-200">
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700">Organization</label>
                    <input type="text"
                        value="{{ $project->organization->name }}"
                        class="mt-1 w-full rounded-lg border-slate-200 bg-slate-100"
                        readonly>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700">Inclusive Start</label>
                    <input type="date" name="inclusive_start"
                        value="{{ old('inclusive_start', $proposalData->start_date ?? '') }}"
                        class="mt-1 w-full rounded-lg border-slate-200">
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700">Inclusive End</label>
                    <input type="date" name="inclusive_end"
                        value="{{ old('inclusive_end', $proposalData->end_date ?? '') }}"
                        class="mt-1 w-full rounded-lg border-slate-200">
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700">Venue</label>
                    <input type="text" name="venue"
                        value="{{ old('venue', $proposalData->venue_name ?? '') }}"
                        class="mt-1 w-full rounded-lg border-slate-200">
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700">Accommodation</label>
                    <input type="text" name="accommodation"
                        value="{{ old('accommodation') }}"
                        class="mt-1 w-full rounded-lg border-slate-200">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-slate-700">Address</label>
                    <input type="text" name="address"
                        value="{{ old('address') }}"
                        class="mt-1 w-full rounded-lg border-slate-200">
                </div>

            </div>

            {{-- DEPARTURE --}}
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Departure</h3>

                <div class="grid md:grid-cols-3 gap-4">
                    <input type="date" name="departure_date" class="rounded-lg border-slate-200">
                    <input type="time" name="departure_time" class="rounded-lg border-slate-200">
                    <input type="text" name="departure_mode" placeholder="Mode / Carrier"
                        class="rounded-lg border-slate-200">

                    <input type="text" name="departure_plate" placeholder="Plate Number"
                        class="rounded-lg border-slate-200">

                    <input type="text" name="departure_flight" placeholder="Flight Number"
                        class="rounded-lg border-slate-200">
                </div>
            </div>

            {{-- RETURN --}}
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Return</h3>

                <div class="grid md:grid-cols-3 gap-4">
                    <input type="date" name="return_date" class="rounded-lg border-slate-200">
                    <input type="time" name="return_time" class="rounded-lg border-slate-200">
                    <input type="text" name="return_mode" placeholder="Mode / Carrier"
                        class="rounded-lg border-slate-200">

                    <input type="text" name="return_plate" placeholder="Plate Number"
                        class="rounded-lg border-slate-200">

                    <input type="text" name="return_flight" placeholder="Flight Number"
                        class="rounded-lg border-slate-200">
                </div>
            </div>

        </div>

        {{-- ACTION --}}
        <div class="mt-6 flex justify-end">
            <button type="submit"
                class="px-5 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                Generate Form
            </button>
        </div>

    </form>

</div>

</x-app-layout>