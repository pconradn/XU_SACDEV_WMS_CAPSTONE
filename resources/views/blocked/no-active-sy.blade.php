<x-blocked-layout title="No Active School Year" subtitle="School Year Required">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="h-10 w-10 rounded-xl bg-red-50 border border-red-200 flex items-center justify-center">
                <span class="text-red-600 font-bold">!</span>
            </div>

            <div class="flex-1">
                <p class="text-base font-semibold text-slate-900">
                    There is no active school year set.
                </p>

                <p class="mt-1 text-sm text-slate-600">
                    Please contact the SAcDev staff to activate a school year before continuing.
                </p>

                <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-600">
                        Tip: Once activated, refresh the page or log in again.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-blocked-layout>
