<x-app-layout>

    <div class="min-h-[60vh] flex items-center justify-center px-6">
        <div class="text-center max-w-md">

            <h1 class="text-2xl font-semibold text-slate-900">
                Access Denied
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                {{ $exception->getMessage() ?: 'This action is restricted based on your role or organization context.' }}
            </p>

            <div class="mt-6 flex justify-center gap-2">

                {{-- BACK --}}
                <a href="{{ url()->previous() }}"
                   class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">
                    Go Back
                </a>

                {{-- DASHBOARD (DYNAMIC) --}}
                <a href="{{ auth()->user()?->system_role === 'sacdev_admin'
                        ? route('admin.home')
                        : route('org.home') }}"
                   class="px-4 py-2 rounded-lg border border-slate-200 text-sm text-slate-700 hover:bg-slate-50">
                    Dashboard
                </a>

            </div>

        </div>
    </div>

</x-app-layout>