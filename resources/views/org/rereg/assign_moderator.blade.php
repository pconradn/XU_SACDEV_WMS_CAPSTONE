<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900">
                Assign / Change Moderator
            </h2>
            <div class="mt-1 text-sm text-slate-600">
                Target SY ID: <span class="font-semibold">{{ $targetSyId }}</span>
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

        {{-- CURRENT MODERATOR --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">Currently assigned (Target SY)</div>

            @if($current)
                <div class="mt-2 text-sm text-slate-700 space-y-1">
                    <div><span class="text-slate-500">Name:</span> {{ $current->user?->name ?? '—' }}</div>
                    <div><span class="text-slate-500">Email:</span> {{ $current->user?->email ?? '—' }}</div>

                    <div class="mt-2 inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                        Active moderator for this SY
                    </div>
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
                            class="inline-flex items-center rounded-lg border border-indigo-200 bg-white px-3 py-2 text-sm font-semibold text-indigo-900 hover:bg-indigo-100">
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

            @if($current)
                <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 text-sm">
                    You are about to replace the current moderator for this target SY.
                    If the new moderator’s account is still <span class="font-semibold">not activated</span>, the system may generate a new temporary password.
                    If the account is already activated, no new temporary password will be issued.
                </div>
            @endif

            @php
                $prefillName =
                    old('full_name')
                    ?? ($current->user?->name ?? null)
                    ?? (isset($suggested) && $suggested ? $suggested->name : '');

                $prefillEmail =
                    old('email')
                    ?? ($current->user?->email ?? null)
                    ?? (isset($suggested) && $suggested ? $suggested->email : '');
            @endphp

            <form method="POST"
                  action="{{ route('org.rereg.assign.moderator.store') }}"
                  onsubmit="return confirm('{{ $current
                        ? "Replace the current moderator for this target SY? Continue?"
                        : "Assign a moderator for this target SY? Continue?" }}');">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Full Name</label>
                        <input id="fullNameInput"
                               name="full_name"
                               value="{{ $prefillName }}"
                               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none"
                               required>
                        @error('full_name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input id="emailInput"
                               name="email"
                               type="email"
                               value="{{ $prefillEmail }}"
                               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none"
                               required>
                        @error('email') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                    <button class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        {{ $current ? 'Save Changes' : 'Assign Moderator' }}
                    </button>

                    <a href="{{ route('org.rereg.index') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Back to Re-registration
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
                    const nameEl = document.getElementById('fullNameInput');
                    const emailEl = document.getElementById('emailInput');
                    if (!nameEl || !emailEl) return;

                    nameEl.value = @json($suggested->name);
                    emailEl.value = @json($suggested->email);
                });
            })();
        </script>
    @endif
</x-app-layout>
