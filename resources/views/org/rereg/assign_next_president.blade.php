<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900">
                Assign Next School Year President
            </h2>
            <div class="mt-1 text-sm text-slate-600">
                Select the target school year where the new president will be assigned.
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                <div class="text-sm">{{ session('status') }}</div>
            </div>
        @endif

        {{-- Target SY selector --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">Target School Year</div>
            <div class="mt-3">
                <label class="block text-sm font-medium text-slate-700">Select School Year</label>

                <form method="GET" action="{{ url()->current() }}" class="mt-1 flex gap-2">
                    <select name="target_sy_id"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none">
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy->id }}" @selected((int)$selectedSyId === (int)$sy->id)>
                                {{ $sy->name ?? ('SY #' . $sy->id) }}
                            </option>
                        @endforeach
                    </select>

                    <button class="inline-flex shrink-0 justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Load
                    </button>
                </form>

                <div class="mt-2 text-xs text-slate-500">
                    The form below updates based on the selected school year.
                </div>
            </div>
        </div>

        @php
            // "Activated" means must_change_password = 0
            $presidentExists = (bool) $current;
            $currentUser = $current?->user;
            $isActivated = $currentUser ? ((int) ($currentUser->must_change_password ?? 0) === 0) : false;

            // Lock assignment if activated president already exists
            $isLocked = $presidentExists && $isActivated;
        @endphp

        {{-- Current assigned --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">Currently assigned</div>

            @if($current)
                <div class="mt-2 text-sm text-slate-700 space-y-1">
                    <div><span class="text-slate-500">Name:</span> {{ $current->user?->name ?? '—' }}</div>
                    <div><span class="text-slate-500">Email:</span> {{ $current->user?->email ?? '—' }}</div>

                    @if($isActivated)
                        <div class="mt-2 inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                            Account activated (already in use) — reassignment locked
                        </div>
                    @else
                        <div class="mt-2 inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-900">
                            Account not yet activated — reassignment allowed
                        </div>
                    @endif
                </div>
            @else
                <div class="mt-2 text-sm text-slate-600">
                    No president has been assigned yet for this target school year.
                </div>
            @endif
        </div>

        {{-- Assign new president --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 text-sm font-semibold text-slate-900">Assign new president</div>

            @if($isLocked)
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                    <div class="font-semibold">Assignment Locked</div>
                    <div class="mt-1 text-sm">
                        A president is already assigned for the selected school year, and the account has been activated.
                        For audit and accountability, you can no longer replace the president for this school year.
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('org.provision.next_president.store') }}"
                  onsubmit="return confirm('Assigning will create/assign an account for this person for the selected school year. Continue?');">
                @csrf

                {{-- carry selected SY --}}
                <input type="hidden" name="target_sy_id" value="{{ (int) $selectedSyId }}">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Full Name</label>
                        <input name="full_name" value="{{ old('full_name') }}"
                               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none disabled:bg-slate-100"
                               required
                               @disabled($isLocked)>
                        @error('full_name') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Student ID</label>
                        <input name="student_id" value="{{ old('student_id') }}"
                               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none disabled:bg-slate-100"
                               required
                               @disabled($isLocked)>
                        @error('student_id') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input name="email" type="email" value="{{ old('email') }}"
                               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none disabled:bg-slate-100"
                               required
                               @disabled($isLocked)>
                        @error('email') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                    <button class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 disabled:cursor-not-allowed disabled:bg-slate-300"
                            @disabled($isLocked)>
                        Assign President for Selected SY
                    </button>

                    <a href="{{ route('org.rereg.index') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Back to Re-registration
                    </a>
                </div>

                <div class="mt-3 text-xs text-slate-500">
                    Temporary password will be logged for now. Email sending depends on mail configuration.
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
