<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900">
                Assign / Change Moderator
            </h2>
            <div class="mt-1 text-sm text-slate-600">
                Select the target school year for re-registration.
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                <div class="text-sm">{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-900">
                <div class="text-sm font-semibold">Please fix the following:</div>
                <ul class="mt-2 list-disc pl-5 text-sm space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TARGET SY SELECTOR --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <label class="block text-sm font-medium text-slate-700 mb-1">Target School Year</label>
            <div class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                {{ $currentSy->label ?? ('SY ' . $currentSy->name ?? $selectedSyId) }}
            </div>

            <div class="mt-2 text-xs text-slate-500">
                The moderator info below updates based on the selected school year.
            </div>
        </div>

        @php
            $currentUser = $current?->user;
            $isActivated = $currentUser ? ((int) ($currentUser->must_change_password ?? 0) === 0) : false;

       
            $hasB5 = (bool) ($hasB5ForCurrentModerator ?? false);
            $isLocked = (bool) ($registered ?? false);

            $prefillName =
                old('full_name')
                ?? ($currentUser?->name ?? null)
                ?? (isset($suggested) && $suggested ? $suggested->name : '');

            $prefillEmail =
                old('email')
                ?? ($currentUser?->email ?? null)
                ?? (isset($suggested) && $suggested ? $suggested->email : '');
        @endphp

        {{-- CURRENT MODERATOR --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">Currently assigned (Selected Target SY)</div>

            @if($current)
                <div class="mt-2 text-sm text-slate-700 space-y-1">
                    <div><span class="text-slate-500">Name:</span> {{ $currentUser?->name ?? '—' }}</div>
                    <div><span class="text-slate-500">Email:</span> {{ $currentUser?->email ?? '—' }}</div>

                    @if($isActivated)
                        <div class="mt-2 inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                            Account activated
                        </div>
                    @else
                        <div class="mt-2 inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-900">
                            Account not activated yet
                        </div>
                    @endif

                    @if($hasB5)
                        <div class="mt-2 inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                            B5 submission exists for this SY
                        </div>
                    @endif
                </div>
            @else
                <div class="mt-2 text-sm text-slate-600">
                    No moderator assigned yet for this target school year.
                </div>
            @endif
        </div>

        {{-- SUGGESTED MODERATOR --}}
        @if(isset($suggested) && $suggested)
            <div class="mb-6 rounded-2xl border border-indigo-200 bg-indigo-50 p-5 shadow-sm">
                <div class="text-sm font-semibold text-indigo-900">Suggested moderator (from previous / active year)</div>
                <div class="mt-2 text-sm text-indigo-900 space-y-1">
                    <div><span class="text-indigo-700">Name:</span> {{ $suggested->name }}</div>
                    <div><span class="text-indigo-700">Email:</span> {{ $suggested->email }}</div>
                </div>

                <div class="mt-3">
                    <button type="button"
                            id="useSuggestedBtn"
                            class="inline-flex items-center rounded-lg border border-indigo-200 bg-white px-3 py-2 text-sm font-semibold text-indigo-900 hover:bg-indigo-100"
                            @disabled($isLocked)>
                        Use suggested
                    </button>
                </div>
            </div>
        @endif

        {{-- ASSIGN / REPLACE --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-2 text-sm font-semibold text-slate-900">
                {{ $current ? 'Change moderator for this target SY' : 'Assign moderator for this target SY' }}
            </div>

                @if($isLocked)
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                        <div class="font-semibold">Assignment Locked</div>
                        <div class="mt-1 text-sm">
                            Moderator assignment is locked because the organization is already registered for this school year.
                        </div>
                    </div>
                @elseif($current)
                    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 text-sm">
                        You are about to replace the current moderator for this target SY.
                        Any existing moderator submissions will be permanently deleted.
                    </div>
                @endif
                @if($hasB5 && !$isLocked)
                    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-900 text-sm">
                        Warning: This moderator already has submissions for this school year.
                        Changing the moderator will delete those records.
                    </div>
                @endif

            <form method="POST"
                  action="{{ route('org.rereg.assign.moderator.store') }}"
                  onsubmit="return confirm('{{ $current
                        ? "Replace the current moderator for this target SY? Continue?"
                        : "Assign a moderator for this target SY? Continue?" }}');">
                @csrf

                {{-- Carry selected SY --}}
                <input type="hidden" name="target_sy_id" value="{{ (int) $selectedSyId }}">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Prefix</label>
                        <input name="prefix"
                            value="{{ old('prefix') }}"
                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            @disabled($isLocked)>
                        @error('prefix') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">First Name</label>
                        <input id="firstNameInput"
                            name="first_name"
                            value="{{ old('first_name') }}"
                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            required
                            @disabled($isLocked)>
                        @error('first_name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Middle Initial</label>
                        <input name="middle_initial"
                            value="{{ old('middle_initial') }}"
                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            @disabled($isLocked)>
                        @error('middle_initial') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Last Name</label>
                        <input id="lastNameInput"
                            name="last_name"
                            value="{{ old('last_name') }}"
                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            required
                            @disabled($isLocked)>
                        @error('last_name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input id="emailInput"
                               name="email"
                               type="email"
                               value="{{ $prefillEmail }}"
                               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none disabled:bg-slate-100"
                               required
                               @disabled($isLocked)>
                        @error('email') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                    <button class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 disabled:cursor-not-allowed disabled:bg-slate-300"
                            @disabled($isLocked)>
                        {{ $current ? 'Save Changes' : 'Assign Moderator' }}
                    </button>

                    <a href="{{ route('org.rereg.index') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Cancel
                    </a>
                </div>

                <div class="mt-3 text-xs text-slate-500">
                    Tip: If you’re assigning the same moderator again, just keep the name/email as-is and save.
                </div>
            </form>
        </div>
    </div>

    @if(isset($suggested) && $suggested)
        <script>
        (function () {
            const btn = document.getElementById('useSuggestedBtn');
            if (!btn) return;

            btn.addEventListener('click', function () {

                const full = @json($suggested->name);
                const email = @json($suggested->email);

                const parts = full.split(' ');

                document.getElementById('firstNameInput').value = parts[0] ?? '';
                document.getElementById('lastNameInput').value = parts.slice(1).join(' ') ?? '';

                document.getElementById('emailInput').value = email;
            });
        })();
        </script>
    @endif
</x-app-layout>
