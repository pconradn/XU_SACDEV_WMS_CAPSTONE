<div x-data="{ openReturn: false, openApprove: false, loading: false }">

    @if($registration->status === 'submitted_to_sacdev')

        {{-- DESKTOP / TABLET (RIGHT STICKY) --}}
        <div class="hidden lg:block">

            <div class="sticky top-6">

                <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-4 py-3 border-b border-slate-200 flex items-center gap-2">
                        <i data-lucide="clipboard-check" class="w-4 h-4 text-slate-400"></i>
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                            Actions
                        </span>
                    </div>

                    {{-- BODY --}}
                    <div class="p-4 space-y-3 text-xs">

                        <div class="text-slate-600">
                            Review this submission and take appropriate action.
                        </div>

                        <button type="button"
                                @click="openReturn = true"
                                :disabled="loading"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 
                                       hover:bg-slate-50 transition
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            Return for Revision
                        </button>

                        <button type="button"
                                @click="openApprove = true"
                                :disabled="loading"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-lg 
                                       bg-slate-900 px-3 py-2 text-xs font-semibold text-white 
                                       hover:bg-slate-800 transition
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            Approve Submission
                        </button>

                    </div>

                </div>

            </div>

        </div>


        {{-- MOBILE (BOTTOM BAR) --}}
        <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 
                    border-t border-slate-200 
                    bg-white/95 backdrop-blur
                    shadow-[0_-4px_16px_rgba(0,0,0,0.06)]
                    pb-[env(safe-area-inset-bottom)]">

            <div class="px-4 py-3 flex items-center justify-between gap-3">

                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-slate-800">
                        Review submission
                    </span>
                    <span class="text-[11px] text-slate-500">
                        Return or approve
                    </span>
                </div>

                <div class="flex items-center gap-2">

                    <button type="button"
                            @click="openReturn = true"
                            :disabled="loading"
                            class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 
                                   hover:bg-slate-50 transition">
                        Return
                    </button>

                    <button type="button"
                            @click="openApprove = true"
                            :disabled="loading"
                            class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white 
                                   hover:bg-slate-800 transition">
                        Approve
                    </button>

                </div>

            </div>

        </div>

    @else

        {{-- NO ACTION STATE --}}
        <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 
                    border-t border-slate-200 
                    bg-slate-50
                    pb-[env(safe-area-inset-bottom)]">

            <div class="px-4 py-3 text-center text-xs text-slate-500">
                No actions available
            </div>

        </div>

    @endif



    <form id="approveForm"
          method="POST"
          action="{{ route('admin.b2.president.approve', $registration) }}">
        @csrf
    </form>



    {{-- RETURN MODAL --}}
    @include('admin.forms.b2_president.partials._return_modal', [
        'registration' => $registration
    ])

    {{-- APPROVE MODAL --}}
    @include('admin.forms.b2_president.partials._approve_modal', [
        'registration' => $registration
    ])

</div>