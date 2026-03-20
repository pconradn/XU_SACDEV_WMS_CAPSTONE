{{-- TIMELINE --}}
<div>

    <div class="flex items-center justify-between mb-3">
        <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
            Timeline
        </h2>

        <button onclick="document.getElementById('timelineModal').classList.remove('hidden')"
            class="text-xs text-blue-600 hover:underline">
            View all
        </button>
    </div>

    <div class="relative">
        <div class="absolute left-2 top-0 bottom-0 w-px bg-slate-300"></div>

        <div class="space-y-5">

            @forelse($submission->timelines->take(3) as $t)

                @php
                    $config = match($t->action) {
                        'submitted_to_moderator' => ['color' => 'bg-slate-400', 'label' => 'Submitted to Moderator'],
                        'returned_by_moderator' => ['color' => 'bg-red-500', 'label' => 'Returned by Moderator'],
                        'forwarded_to_sacdev' => ['color' => 'bg-blue-500', 'label' => 'Forwarded to SACDEV'],
                        'returned_by_sacdev' => ['color' => 'bg-red-600', 'label' => 'Returned by SACDEV'],
                        'approved_by_sacdev' => ['color' => 'bg-emerald-600', 'label' => 'Approved by SACDEV'],
                        'approval_reverted' => ['color' => 'bg-orange-500', 'label' => 'Approval Reverted'],
                        default => ['color' => 'bg-slate-400', 'label' => ucwords(str_replace('_', ' ', $t->action))]
                    };
                @endphp

                <div class="relative pl-7">

                    {{-- DOT --}}
                    <div class="absolute left-0 top-1 w-4 h-4 rounded-full border border-white shadow {{ $config['color'] }}"></div>

                    {{-- ACTION --}}
                    <div class="text-xs font-semibold text-slate-800">
                        {{ $config['label'] }}
                    </div>

                    {{-- META --}}
                    <div class="text-[11px] text-slate-500">
                        {{ $t->user->name ?? 'System' }} • 
                        {{ $t->created_at->format('M d, Y h:i A') }}
                    </div>

                    {{-- STATUS CHANGE --}}
                    @if($t->old_status && $t->new_status)
                        <div class="text-[11px] text-blue-600 mt-1">
                            {{ $t->old_status }} → {{ $t->new_status }}
                        </div>
                    @endif

                    {{-- REMARKS (RICH TEXT) --}}
                    @if($t->remarks)
                        <div class="text-xs text-slate-700 mt-2 prose prose-sm max-w-none">
                            {!! $t->remarks !!}
                        </div>
                    @endif

                </div>

            @empty
                <div class="text-xs text-slate-400">
                    No timeline yet.
                </div>
            @endforelse

        </div>
    </div>

</div>



{{-- TIMELINE MODAL --}}
<div id="timelineModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-5">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-800">Full Timeline</h3>
            <button onclick="document.getElementById('timelineModal').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600">
                ✕
            </button>
        </div>

        {{-- CONTENT --}}
        <div class="max-h-96 overflow-y-auto pr-2">

            <div class="relative">
                <div class="absolute left-2 top-0 bottom-0 w-px bg-slate-300"></div>

                <div class="space-y-5">

                    @foreach($submission->timelines as $t)

                        @php
                            $config = match($t->action) {
                                'submitted_to_moderator' => ['color' => 'bg-slate-400', 'label' => 'Submitted to Moderator'],
                                'returned_by_moderator' => ['color' => 'bg-red-500', 'label' => 'Returned by Moderator'],
                                'forwarded_to_sacdev' => ['color' => 'bg-blue-500', 'label' => 'Forwarded to SACDEV'],
                                'returned_by_sacdev' => ['color' => 'bg-red-600', 'label' => 'Returned by SACDEV'],
                                'approved_by_sacdev' => ['color' => 'bg-emerald-600', 'label' => 'Approved by SACDEV'],
                                'approval_reverted' => ['color' => 'bg-orange-500', 'label' => 'Approval Reverted'],
                                default => ['color' => 'bg-slate-400', 'label' => ucwords(str_replace('_', ' ', $t->action))]
                            };
                        @endphp

                        <div class="relative pl-7">

                            <div class="absolute left-0 top-1 w-4 h-4 rounded-full border border-white shadow {{ $config['color'] }}"></div>

                            <div class="text-xs font-semibold text-slate-800">
                                {{ $config['label'] }}
                            </div>

                            <div class="text-[11px] text-slate-500">
                                {{ $t->user->name ?? 'System' }} • 
                                {{ $t->created_at->format('M d, Y h:i A') }}
                            </div>

                            @if($t->old_status && $t->new_status)
                                <div class="text-[11px] text-blue-600 mt-1">
                                    {{ $t->old_status }} → {{ $t->new_status }}
                                </div>
                            @endif

                            @if($t->remarks)
                                <div class="text-xs text-slate-700 mt-2 prose prose-sm max-w-none">
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