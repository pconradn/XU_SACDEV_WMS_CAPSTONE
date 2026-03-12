
        {{-- ACTIONS --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h2 class="text-base font-semibold text-slate-900">Actions</h2>
            <p class="text-sm text-slate-500 mt-1">
                Approve, return with remarks, or revert approval (requires remark).
            </p>

            <div class="mt-4 flex flex-wrap gap-2">
                @if(in_array($submission->status, ['forwarded_to_sacdev','returned_by_sacdev'], true))
                    <button type="button"
                            class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                            @click="openApprove=true">
                        Approve
                    </button>

                    <button type="button"
                            class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100"
                            @click="openReturn=true">
                        Return to Organization
                    </button>
                @endif

                @if($submission->status === 'approved_by_sacdev')
                    <button type="button"
                            class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100"
                            @click="openRevert=true">
                        Revert Approval
                    </button>
                @endif

                <a href="{{ route('admin.strategic_plans.index') }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Back to list
                </a>
            </div>
        </div>