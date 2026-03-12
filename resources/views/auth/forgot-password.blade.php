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

            If you forgot your password, you can request a password reset link.
            Enter your registered email address and we will send instructions
            that allow you to securely create a new password.

        </div>

    </div>



    <!-- RIGHT PANEL -->
    <div class="p-10 flex flex-col justify-center">

        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            Reset your password
        </h2>

        <p class="mb-6 text-sm text-gray-600">
            Forgot your password? Enter your email address and we will send
            you a password reset link.
        </p>


        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />

                <x-text-input
                    id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                />

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>


            <!-- Submit -->
            <div class="mt-6">

                <x-primary-button class="w-full justify-center">
                    Email Password Reset Link
                </x-primary-button>

            </div>

        </form>

    </div>

</div>

</x-guest-layout>