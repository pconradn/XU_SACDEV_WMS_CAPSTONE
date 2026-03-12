<x-guest-layout>

<div class="grid grid-cols-2 bg-white shadow-xl rounded-xl overflow-hidden">

    <!-- LEFT PANEL -->
    <div class="bg-blue-900 text-white p-10 flex flex-col justify-between">

        <!-- Header -->
        <div class="text-center">

            <div class="flex justify-center mb-5">
                <div class="w-24 h-24 bg-white rounded-full shadow-md ring-4 ring-blue-200 overflow-hidden">

                    <img src="/images/sacdev-logo.jpg"
                         alt="SACDEV Logo"
                         class="w-full h-full object-cover">

                </div>
            </div>

            <h1 class="text-2xl font-semibold tracking-tight">
                SACDEV
            </h1>

            <h2 class="text-base font-medium mt-1 leading-snug text-blue-100">
                Project Documentation and Approval System
            </h2>

            <p class="mt-2 text-sm text-blue-200">
                Xavier University – Ateneo de Cagayan
            </p>

        </div>


        <!-- Recovery Message -->
        <div class="mt-10 text-sm text-blue-200 leading-relaxed text-center max-w-xs mx-auto">
            You requested to reset your password.  
            Create a new password below to regain access to your account and
            continue managing project document submissions.
        </div>

    </div>



    <!-- RIGHT PANEL -->
    <div class="p-10 flex flex-col justify-center">

        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            Set a new password
        </h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">


            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />

                <x-text-input
                    id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    required
                    autofocus
                />

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>


            <!-- New Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('New Password')" />

                <x-text-input
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>


            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input
                    id="password_confirmation"
                    class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation"
                    required
                />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>


            <!-- Submit -->
            <div class="mt-6">
                <x-primary-button class="w-full justify-center">
                    Reset Password
                </x-primary-button>
            </div>

        </form>

    </div>

</div>

</x-guest-layout>