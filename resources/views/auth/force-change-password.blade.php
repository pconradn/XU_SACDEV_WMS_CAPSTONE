<x-plain-layout>

<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4">

    <div class="w-full max-w-md">

        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">

            <!-- Logo -->
            <div class="flex justify-center mb-5">
                <div class="w-20 h-20 bg-white rounded-full shadow ring-4 ring-blue-100 overflow-hidden">

                    <img src="/images/sacdev-logo.jpg"
                         alt="SACDEV Logo"
                         class="w-full h-full object-cover">

                </div>
            </div>


            <!-- Header -->
            <div class="text-center mb-6">

                <h1 class="text-xl font-semibold text-slate-900">
                    Change Your Password
                </h1>

                <p class="mt-1 text-sm text-slate-600">
                    For security reasons, you must set a new password before continuing.
                </p>

            </div>


            <!-- Errors -->
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


                <!-- New Password -->
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


                <!-- Confirm Password -->
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


                <!-- Submit -->
                <div class="pt-3">
                    <button
                        type="submit"
                        class="inline-flex w-full justify-center rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800"
                    >
                        Update Password
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

</x-plain-layout>