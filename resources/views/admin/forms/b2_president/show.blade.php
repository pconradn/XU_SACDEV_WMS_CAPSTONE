<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">B-2 President Registration (SACDEV Review)</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Org: <span class="font-semibold text-slate-900">{{ $registration->organization->name ?? ('Org #' . $registration->organization_id) }}</span>
                    · Target SY: <span class="font-semibold text-slate-900">{{ $registration->targetSchoolYear->label ?? $registration->target_school_year_id }}</span>
                </p>
            </div>

            <a href="{{ route('admin.b2.president.index') }}"
               class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                Back to List
            </a>
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

        @include('org.forms.b2_president.partials._status_banner', ['registration' => $registration])

        {{-- SACDEV Action Panel --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
            <h3 class="text-base font-semibold text-slate-900">SACDEV Actions</h3>

            @if($registration->status !== 'submitted_to_sacdev')
                <div class="mt-2 text-sm text-slate-600">
                    Actions are only available when the status is <span class="font-semibold">submitted_to_sacdev</span>.
                </div>
            @else
                <div class="mt-3 grid grid-cols-1 gap-3">
                    <form method="POST" action="{{ route('admin.b2.president.return', $registration->id) }}">
                        @csrf
                        <label class="block text-sm font-medium text-slate-700">
                            Return Remarks <span class="text-red-600">*</span>
                        </label>
                        <textarea name="sacdev_remarks" rows="3"
                                  class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-200"
                                  placeholder="State what needs to be corrected...">{{ old('sacdev_remarks') }}</textarea>

                        @error('sacdev_remarks')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-3">
                            <button type="submit"
                                    class="inline-flex justify-center rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-900 hover:bg-amber-100">
                                Return to Organization
                            </button>
                        </div>
                    </form>

                    <div class="border-t border-slate-100 pt-4">
                        <form method="POST" action="{{ route('admin.b2.president.approve', $registration->id) }}">
                            @csrf

                            <label class="block text-sm font-medium text-slate-700">
                                Approval Note (optional)
                            </label>
                            <textarea name="sacdev_remarks" rows="2"
                                      class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-200"
                                      placeholder="Optional note for approval..."></textarea>

                            <div class="mt-3">
                                <button type="submit"
                                        class="inline-flex justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                    Approve
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- Read-only view of the form --}}
        <div class="space-y-0">
            @include('org.forms.b2_president.partials._photo_id', ['isLocked' => $isLocked])
            @include('org.forms.b2_president.partials._personal_info', ['isLocked' => $isLocked])
            @include('org.forms.b2_president.partials._contact_info', ['isLocked' => $isLocked])
            @include('org.forms.b2_president.partials._family_info', ['isLocked' => $isLocked])
            @include('org.forms.b2_president.partials._education_info', ['isLocked' => $isLocked])
            @include('org.forms.b2_president.partials._leaderships', ['isLocked' => $isLocked, 'leaderships' => $registration->leaderships])
            @include('org.forms.b2_president.partials._trainings', ['isLocked' => $isLocked, 'trainings' => $registration->trainings])
            @include('org.forms.b2_president.partials._awards', ['isLocked' => $isLocked, 'awards' => $registration->awards])
            @include('org.forms.b2_president.partials._skills', ['isLocked' => $isLocked])
            @include('org.forms.b2_president.partials._certification', ['isLocked' => $isLocked])
        </div>
    </div>
</x-app-layout>
