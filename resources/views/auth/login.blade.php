<x-guest-layout>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-slate-100 to-white px-4 py-6">

    <div class="w-full max-w-5xl rounded-2xl border border-slate-200 bg-white shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">

        {{-- LEFT PANEL --}}
        <div class="bg-gradient-to-b from-blue-900 to-blue-800 text-white p-8 lg:p-10 flex flex-col justify-between">

            {{-- TOP --}}
            <div class="text-center">

                {{-- LOGO --}}
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 bg-white rounded-full shadow-md ring-4 ring-blue-200 overflow-hidden">
                        <img src="/images/sacdev-logo.jpg"
                             alt="SACDEV Logo"
                             class="w-full h-full object-cover">
                    </div>
                </div>

                {{-- TITLE --}}
                <h1 class="text-xl font-semibold tracking-tight">
                    SACDEV
                </h1>

                <h2 class="text-sm font-medium mt-1 text-blue-100">
                    Project Documentation & Approval System
                </h2>

                <p class="mt-1 text-xs text-blue-200">
                    Xavier University – Ateneo de Cagayan
                </p>

            </div>


            {{-- DESCRIPTION --}}
            <div class="mt-6 text-xs text-blue-200 leading-relaxed text-center max-w-xs mx-auto hidden lg:block">
                Manage submissions, approvals, and project workflows for student organizations in one centralized platform.
            </div>

        </div>


        {{-- RIGHT PANEL --}}
        <div class="relative p-6 sm:p-8 lg:p-10">

            {{-- FLOATING UPDATE BUTTON --}}
            <div class="absolute top-4 right-4">

                @include('auth.partials._system_updates_modal')

            </div>

            <div class="max-w-md mx-auto w-full">

                {{-- HEADER --}}
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-slate-900">
                        Login to your account
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Enter your university credentials to continue
                    </p>
                </div>


                <x-auth-session-status class="mb-4 text-xs" :status="session('status')" />


                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf


                    {{-- EMAIL --}}
                    <div>
                        <label class="text-xs font-semibold text-slate-600">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        >

                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>


                    {{-- PASSWORD --}}
                    <div>
                        <label class="text-xs font-semibold text-slate-600">
                            Password
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        >

                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                    </div>


                    {{-- OPTIONS --}}
                    <div class="flex items-center justify-between text-xs">

                        <label class="flex items-center text-slate-600">
                            <input type="checkbox" name="remember" class="mr-2 rounded border-slate-300">
                            Remember me
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-blue-600 hover:underline">
                                Forgot password?
                            </a>
                        @endif

                    </div>


                    {{-- ACTIONS --}}
                    <div class="pt-2 space-y-3">

                        {{-- LOGIN --}}
                        <button
                            type="submit"
                            class="w-full rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 transition shadow-sm">
                            Log in
                        </button>

                        {{-- DIVIDER --}}
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-px bg-slate-200"></div>
                            <span class="text-[10px] text-slate-400">or</span>
                            <div class="flex-1 h-px bg-slate-200"></div>
                        </div>


      


                        {{-- CLEARANCE CHECK --}}
                        <a href="{{ route('clearance.public.index') }}"
                           class="flex items-center justify-center gap-2 w-full rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold py-2.5 transition">

                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>

                            Check Clearance Status

                        </a>

                        {{-- HELPER TEXT --}}
                        <p class="text-[11px] text-slate-400 text-center">
                            For students verifying clearance without logging in
                        </p>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</x-guest-layout>