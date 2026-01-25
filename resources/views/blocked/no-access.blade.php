<x-blocked-layout title="No Access" subtitle="Role/Assignment Required">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="h-10 w-10 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center">
                <span class="text-amber-600 font-bold">!</span>
            </div>

            <div class="flex-1">
                <p class="text-base font-semibold text-slate-900">
                    No access for the current active school year.
                </p>

                <p class="mt-1 text-sm text-slate-600">
                    Your account has no active role or project assignment under the active school year.
                </p>

                <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-600">
                        If you believe this is a mistake, please contact your organization president or SAcDev staff.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-blocked-layout>
