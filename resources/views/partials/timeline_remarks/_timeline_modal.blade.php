    {{-- ========================= --}}
    {{-- TIMELINE MODAL --}}
    {{-- ========================= --}}
    <div x-show="openTimeline" x-cloak x-transition
        class="fixed inset-0 z-50 flex items-center justify-center p-4">

        {{-- BACKDROP --}}
        <div class="absolute inset-0 bg-slate-900/50" @click="openTimeline = false"></div>

        {{-- MODAL --}}
        <div class="relative w-full max-w-2xl rounded-2xl bg-white border border-slate-200 shadow-xl">

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-5 py-4 border-b">
                <h3 class="text-lg font-semibold text-slate-900">
                    Submission Timeline
                </h3>

                <button @click="openTimeline = false"
                        class="text-slate-400 hover:text-slate-600 text-lg">
                    ✕
                </button>
            </div>

            {{-- CONTENT --}}
            <div class="p-5 max-h-[70vh] overflow-y-auto">

                <div class="relative">

                    {{-- LINE --}}
                    <div class="absolute left-3 top-0 bottom-0 w-px bg-slate-200"></div>

                    <div class="space-y-6">

                        @foreach($submission->timelines as $t)

                            @php
                                $config = match($t->action) {
                                    'submitted_to_moderator' => ['color' => 'bg-slate-400', 'label' => 'Submitted to Moderator'],
                                    'returned_by_moderator' => ['color' => 'bg-rose-500', 'label' => 'Returned by Moderator'],
                                    'forwarded_to_sacdev' => ['color' => 'bg-blue-500', 'label' => 'Forwarded to SACDEV'],
                                    'returned_by_sacdev' => ['color' => 'bg-rose-600', 'label' => 'Returned by SACDEV'],
                                    'approved_by_sacdev' => ['color' => 'bg-emerald-600', 'label' => 'Approved by SACDEV'],
                                    'approval_reverted' => ['color' => 'bg-amber-500', 'label' => 'Approval Reverted'],
                                    default => ['color' => 'bg-slate-400', 'label' => ucwords(str_replace('_', ' ', $t->action))]
                                };
                            @endphp

                            <div class="relative pl-10">

                                {{-- DOT --}}
                                <div class="absolute left-1.5 top-1 w-4 h-4 rounded-full border-2 border-white shadow {{ $config['color'] }}"></div>

                                {{-- TITLE --}}
                                <div class="text-sm font-semibold text-slate-800">
                                    {{ $config['label'] }}
                                </div>

                                {{-- META --}}
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $t->user->name ?? 'System' }} • 
                                    {{ $t->created_at->format('M d, Y h:i A') }}
                                </div>

                                {{-- STATUS CHANGE --}}
                                @if($t->old_status && $t->new_status)
                                    <div class="text-xs text-blue-600 mt-1">
                                        {{ $t->old_status }} → {{ $t->new_status }}
                                    </div>
                                @endif

                                {{-- REMARKS --}}
                                @if($t->remarks)
                                    <div class="mt-2 text-sm text-slate-700 prose prose-sm max-w-none">
                                        {!! $t->remarks !!}
                                    </div>
                                @endif

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>
    </div>