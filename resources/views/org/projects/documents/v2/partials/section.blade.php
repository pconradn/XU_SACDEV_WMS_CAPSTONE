<div class="bg-white border rounded-2xl shadow-sm">

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b flex items-center justify-between">

        <h3 class="text-sm font-semibold text-slate-800">
            {{ $title }}
        </h3>

        {{-- OPTIONAL: COUNT --}}
        <span class="text-xs text-slate-400">
            {{ count($forms) }} forms
        </span>

    </div>


    {{-- BODY --}}
    <div class="divide-y">

        @forelse($forms as $form)

            <div class="flex items-center justify-between px-5 py-4">

         
                <div class="space-y-1">

                    {{-- FORM NAME --}}
                    <p class="text-sm font-medium text-slate-800">
                        {{ $form['name'] }}
                    </p>

                    {{-- STATUS + WAITING --}}
                    <div class="flex items-center gap-2 flex-wrap">

                        {{-- STATUS BADGE --}}
                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-full ring-1 {{ $form['status_class'] }}">
                            {{ $form['status_label'] }}
                        </span>

                        {{-- WAITING ROLE --}}
                        @if(!empty($form['waiting_for']))
                            <span class="text-xs text-slate-500">
                                Waiting for {{ str_replace('_',' ', ucfirst($form['waiting_for'])) }}
                            </span>
                        @endif

                    </div>

                </div>


            <div class="flex items-center gap-2">

                {{-- CREATE --}}
                @if($form['can_create'] && $form['create_url'])
                    <a href="{{ $form['create_url'] }}"
                    class="text-xs px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Create
                    </a>

                {{-- CONTINUE (EDIT) --}}
                @elseif($form['can_edit'] && $form['edit_url'])
                    <a href="{{ $form['edit_url'] }}"
                    class="text-xs px-3 py-2 bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200">
                        Continue
                    </a>

                {{-- REVIEW --}}
                @elseif($form['can_review'] && $form['view_url'])
                    <a href="{{ $form['view_url'] }}"
                    class="text-xs px-3 py-2 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200">
                        Review
                    </a>

                {{-- VIEW (fallback) --}}
                @elseif($form['document'] && $form['view_url'])
                    <a href="{{ $form['view_url'] }}"
                    class="text-xs px-3 py-2 bg-slate-200 text-slate-700 rounded-md hover:bg-slate-300">
                        View
                    </a>
                @endif

            </div>

            </div>

        @empty

            <div class="px-5 py-4 text-sm text-slate-500">
                No forms available.
            </div>

        @endforelse

    </div>

</div>