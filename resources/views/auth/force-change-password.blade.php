<x-plain-layout>
    <div class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
        <div class="w-full max-w-md">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                
                <div class="mb-4">
                    <h1 class="text-xl font-semibold text-slate-900">
                        Change your password
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        This is required before you can continue.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                        <div class="font-semibold mb-1">Please fix the following:</div>
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.force.update') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            New Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            required
                            minlength="8"
                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Confirm New Password
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            minlength="8"
                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none"
                        >
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="inline-flex w-full justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
                        >
                            Update Password
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-plain-layout>
