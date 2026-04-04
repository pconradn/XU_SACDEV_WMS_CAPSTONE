<x-plain-layout>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-slate-100 to-white px-4 py-6">

    <div class="w-full max-w-md">

        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-6 shadow-sm">

            {{-- HEADER ICON + TITLE --}}
            <div class="text-center">

                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                        <i data-lucide="lock" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>

                <h1 class="text-lg font-semibold text-slate-900">
                    Update Your Password
                </h1>

                <p class="mt-1 text-xs text-slate-500 max-w-xs mx-auto">
                    For security, please set a new password before continuing.
                </p>

            </div>


            {{-- ERROR --}}
            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800">
                    <div class="font-semibold mb-1">Please fix the following:</div>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- FORM --}}
            <form method="POST" action="{{ route('password.force.update') }}" class="mt-5 space-y-4">
                @csrf


                {{-- NEW PASSWORD --}}
                <div>
                    <label class="text-xs font-semibold text-slate-600">
                        New Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        minlength="8"
                        class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition"
                    >

                    <p class="mt-1 text-[11px] text-slate-400">
                        Must be at least 8 characters.
                    </p>
                </div>


                {{-- CONFIRM PASSWORD --}}
                <div>
                    <label class="text-xs font-semibold text-slate-600">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        minlength="8"
                        class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition"
                    >
                </div>


                {{-- ACTION --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2.5 transition shadow-sm"
                    >
                        Update Password
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

</x-plain-layout>