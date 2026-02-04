<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900">
                Assign Moderator (Target School Year)
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

        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">Currently assigned moderator</div>

            @if($current)
                <div class="mt-2 text-sm text-slate-700">
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

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 text-sm font-semibold text-slate-900">Assign / replace moderator</div>

            <form method="POST" action="{{ route('org.rereg.assign.moderator.store') }}"
                  onsubmit="return confirm('Assigning will create/assign an account for this moderator for the target school year. Continue?');">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Full Name</label>
                        <input name="full_name" value="{{ old('full_name') }}"
                               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none"
                               required>
                        @error('full_name') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input name="email" type="email" value="{{ old('email') }}"
                               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none"
                               required>
                        @error('email') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                    <button class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Assign Moderator for Target SY
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
