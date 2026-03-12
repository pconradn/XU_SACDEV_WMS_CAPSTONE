<x-guest-layout>

<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">


    <div class="w-full max-w-4xl bg-white shadow-xl rounded-xl overflow-hidden grid grid-cols-2">

    
        <div class="bg-blue-900 text-white p-10 flex flex-col justify-between">

            <!-- Header -->
            <div class="text-center">

                <!-- Logo -->
                <div class="flex justify-center mb-5">
                    <div class="w-24 h-24 bg-white rounded-full shadow-md ring-4 ring-blue-200 overflow-hidden">

                        <img src="/images/sacdev-logo.jpg"
                            alt="SACDEV Logo"
                            class="w-full h-full object-cover">

                    </div>
                </div>

                <!-- Title -->
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


            <!-- Description -->
            <div class="mt-10 text-sm text-blue-200 leading-relaxed text-center max-w-xs mx-auto">
                This system facilitates the submission, review, and approval of
                student organization project documents required by the SACDEV office.
            </div>

        </div>

      
        <div class="p-10">

            <h2 class="text-xl font-semibold text-gray-800 mb-6">
                Login to your account
            </h2>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                
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


                
                <div class="mt-4">

                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input
                        id="password"
                        class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                </div>


         
                <div class="flex items-center justify-between mt-4">

                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="mr-2">
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-blue-700 hover:underline">
                            Forgot password?
                        </a>
                    @endif

                </div>


              
                <div class="mt-6">

                    <button
                        type="submit"
                        class="w-full bg-blue-900 hover:bg-blue-800 text-white font-medium py-2 rounded-md">
                        Log in
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</x-guest-layout>