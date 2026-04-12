<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4 space-y-5">

    {{-- HEADER --}}
    <div class="flex items-start justify-between gap-3">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center">
                <i data-lucide="users" class="w-4 h-4 text-purple-600"></i>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                    Participants Information
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    List all students participating in the off-campus activity along with their contact and guardian details.
                </p>
            </div>
        </div>

        @if(!$isReadOnly)
        <button
            type="button"
            onclick="addParticipant()"
            class="flex items-center gap-1 text-xs bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg font-semibold shadow-sm transition">
            <i data-lucide="plus" class="w-3 h-3"></i>
            Add
        </button>
        @endif
    </div>

    {{-- TABLE --}}
    <div class="rounded-2xl border bg-white shadow-sm overflow-hidden
        {{ $errors->has('participants') || $errors->has('participants.*') || $errors->has('participants.*.*')
            ? 'border-rose-300 ring-1 ring-rose-400'
            : 'border-slate-200' }}">


        <div class="overflow-x-auto">

            <table class="min-w-[900px] md:min-w-[1100px] text-xs">

                {{-- HEADER --}}
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-slate-600 uppercase">Full Name</th>
                        <th class="px-3 py-2 text-left font-semibold text-slate-600 uppercase">Course & Year</th>
                        <th class="px-3 py-2 text-left font-semibold text-slate-600 uppercase">Student Mobile</th>
                        <th class="px-3 py-2 text-left font-semibold text-slate-600 uppercase">Parent / Guardian</th>
                        <th class="px-3 py-2 text-left font-semibold text-slate-600 uppercase">Parent Mobile</th>
                        @if(!$isReadOnly)
                        <th class="px-3 py-2 text-center font-semibold text-slate-600 uppercase">Action</th>
                        @endif
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody id="participantsBody" class="divide-y divide-slate-100">

                @if($participants->count())

                    @foreach($participants as $p)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-3 py-2">
                            <input type="text"
                                name="participants[{{ $loop->index }}][student_name]"
                                value="{{ $p->student_name }}"
                                placeholder="Full name"
                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs focus:ring-2 focus:ring-purple-500"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="participants[{{ $loop->index }}][course_year]"
                                value="{{ $p->course_year }}"
                                placeholder="e.g. BSIT 3"
                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs focus:ring-2 focus:ring-purple-500"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="participants[{{ $loop->index }}][student_mobile]"
                                value="{{ $p->student_mobile }}"
                                placeholder="09XXXXXXXXX"
                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs focus:ring-2 focus:ring-purple-500"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="participants[{{ $loop->index }}][parent_name]"
                                value="{{ $p->parent_name }}"
                                placeholder="Parent / Guardian name"
                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs focus:ring-2 focus:ring-purple-500"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="participants[{{ $loop->index }}][parent_mobile]"
                                value="{{ $p->parent_mobile }}"
                                placeholder="09XXXXXXXXX"
                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs focus:ring-2 focus:ring-purple-500"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        @if(!$isReadOnly)
                        <td class="px-3 py-2 text-center">
                            <button
                                type="button"
                                onclick="removeParticipant(this)"
                                class="text-rose-600 hover:text-rose-800 transition">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </td>
                        @endif

                    </tr>

                    @endforeach

                @else

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-3 py-2">
                            <input type="text"
                                name="participants[0][student_name]"
                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="participants[0][course_year]"
                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="participants[0][student_mobile]"
                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="participants[0][parent_name]"
                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="participants[0][parent_mobile]"
                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        @if(!$isReadOnly)
                        <td class="px-3 py-2 text-center text-slate-400 text-xs">
                            —
                        </td>
                        @endif

                    </tr>

                @endif

                </tbody>

            </table>

        </div>

    </div>

    {{-- HELPER --}}
    <p class="text-[11px] text-slate-400">
        Ensure all participant information is accurate. This will be used for safety and emergency contact purposes.
    </p>

</div>