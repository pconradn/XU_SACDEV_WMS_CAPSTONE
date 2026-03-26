<div class="bg-white border rounded-2xl shadow-sm">

    @php
        $pending = $sectionCounts[$sectionKey] ?? 0;
    @endphp

    <div class="px-5 py-4 border-b flex items-center justify-between">

        <div class="flex items-center gap-2">

            <h3 class="text-sm font-semibold text-slate-800">
                {{ $title }}
            </h3>

            {{-- 🔴 Pending badge --}}
            @if($pending > 0)
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-semibold">
                    {{ $pending }} pending
                </span>
            @endif

        </div>

        <span class="text-xs text-slate-400">
            {{ count($forms) }} forms
        </span>

    </div>


    <div class="divide-y">

        @forelse($forms as $form)

            @php
                $isMine = $form['is_waiting_for_me'] ?? false;
            @endphp

            <div class="flex items-center justify-between px-5 py-4
                {{ $isMine ? 'bg-blue-50/60 border-l-4 border-blue-500' : '' }}">

                {{-- LEFT --}}
                <div class="space-y-1">

                    {{-- FORM NAME --}}
                    <p class="text-sm font-medium text-slate-800">
                        {{ $form['name'] }}
                    </p>

                    {{-- STATUS + FLOW --}}
                    <div class="flex items-center gap-2 flex-wrap">

                        {{-- STATUS --}}
                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-full ring-1 {{ $form['status_class'] }}">
                            {{ $form['status_label'] }}
                        </span>

                        {{-- YOUR TURN --}}
                        @if($isMine)
                            <span class="text-xs font-semibold text-blue-700">
                                • Awaiting your approval
                            </span>

                        {{-- WAITING --}}
                        @elseif(!empty($form['waiting_for']))
                            <span class="text-xs text-slate-500">
                                • Waiting for {{ str_replace('_',' ', ucfirst($form['waiting_for'])) }}
                            </span>
                        @endif

                    </div>

                </div>


                {{-- ACTIONS --}}
                <div class="flex items-center gap-2">

                    @if($form['can_create'] && $form['create_url'])
                        <a href="{{ $form['create_url'] }}"
                        class="text-xs px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Create
                        </a>

                    @elseif($form['can_edit'] && $form['edit_url'])
                        <a href="{{ $form['edit_url'] }}"
                        class="text-xs px-3 py-2 bg-amber-100 text-amber-800 rounded-md hover:bg-amber-200">
                            Continue
                        </a>

                    @elseif($form['can_review'] && $form['view_url'])
                        <a href="{{ $form['view_url'] }}"
                        class="text-xs px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Review
                        </a>

                    @elseif($form['document'] && $form['view_url'])
                        <a href="{{ $form['view_url'] }}"
                        class="text-xs px-3 py-2 bg-slate-200 text-slate-700 rounded-md hover:bg-slate-300">
                            View
                        </a>
                    @endif

                </div>

            </div>

        @empty

            <div class="px-5 py-6 text-sm text-slate-500 text-center">
                No forms available in this section.
            </div>

        @endforelse

    </div>

</div>