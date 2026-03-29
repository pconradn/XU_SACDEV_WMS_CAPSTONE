<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4 flex items-start justify-between gap-3">
        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Participants Information
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                List all students participating in the off-campus activity along with their contact and guardian details.
            </p>
        </div>

        @if(!$isReadOnly)
        <button
            type="button"
            onclick="addParticipant()"
            class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-semibold shadow-sm">
            + Add Participant
        </button>
        @endif
    </div>


    {{-- TABLE --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                {{-- HEADER --}}
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">
                            Full Name
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">
                            Course & Year
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">
                            Student Mobile
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">
                            Parent / Guardian
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">
                            Parent Mobile
                        </th>
                        @if(!$isReadOnly)
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">
                            Action
                        </th>
                        @endif
                    </tr>
                </thead>


                {{-- BODY --}}
                <tbody id="participantsBody" class="divide-y">

                @if($participants->count())

                    @foreach($participants as $p)

                    <tr>

                        {{-- NAME --}}
                        <td class="px-4 py-2">
                            <input type="text"
                                name="participants[{{ $loop->index }}][student_name]"
                                value="{{ $p->student_name }}"
                                placeholder="Full name"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        {{-- COURSE --}}
                        <td class="px-4 py-2">
                            <input type="text"
                                name="participants[{{ $loop->index }}][course_year]"
                                value="{{ $p->course_year }}"
                                placeholder="e.g. BSIT 3"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        {{-- STUDENT MOBILE --}}
                        <td class="px-4 py-2">
                            <input type="text"
                                name="participants[{{ $loop->index }}][student_mobile]"
                                value="{{ $p->student_mobile }}"
                                placeholder="09XXXXXXXXX"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        {{-- PARENT NAME --}}
                        <td class="px-4 py-2">
                            <input type="text"
                                name="participants[{{ $loop->index }}][parent_name]"
                                value="{{ $p->parent_name }}"
                                placeholder="Parent / Guardian name"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        {{-- PARENT MOBILE --}}
                        <td class="px-4 py-2">
                            <input type="text"
                                name="participants[{{ $loop->index }}][parent_mobile]"
                                value="{{ $p->parent_mobile }}"
                                placeholder="09XXXXXXXXX"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        {{-- ACTION --}}
                        @if(!$isReadOnly)
                        <td class="px-4 py-2 text-center">
                            <button
                                type="button"
                                onclick="removeParticipant(this)"
                                class="text-rose-600 hover:text-rose-800 text-xs font-semibold">
                                Remove
                            </button>
                        </td>
                        @endif

                    </tr>

                    @endforeach

                @else

                    {{-- EMPTY ROW --}}
                    <tr>

                        <td class="px-4 py-2">
                            <input type="text"
                                name="participants[0][student_name]"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-4 py-2">
                            <input type="text"
                                name="participants[0][course_year]"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-4 py-2">
                            <input type="text"
                                name="participants[0][student_mobile]"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-4 py-2">
                            <input type="text"
                                name="participants[0][parent_name]"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-4 py-2">
                            <input type="text"
                                name="participants[0][parent_mobile]"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        @if(!$isReadOnly)
                        <td class="px-4 py-2 text-center text-slate-400 text-xs">
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
    <p class="text-[11px] text-slate-400 mt-2">
        Ensure all participant information is accurate. This will be used for safety, coordination, and emergency contact purposes.
    </p>

</div>