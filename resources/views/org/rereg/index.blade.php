<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">

        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900">
                Organization Re-Registration
            </h2>

            <div class="text-sm text-slate-600 mt-1">
                Select the target school year (SY) and complete required forms.
            </div>
        </div>


        {{-- Status message --}}
        @if(session('error'))
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('status'))
            <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('status') }}
            </div>
        @endif


        {{-- Already activated --}}
        @if($isActivated)
            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-900">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                    {{-- LEFT --}}
                    <div>
                        <div class="text-sm font-semibold">
                            Registration Complete
                        </div>

                        <div class="mt-1 text-sm text-emerald-800/90">
                            Your organization is now officially registered for this school year.
                        </div>

                        <div class="mt-2 text-sm text-emerald-800/90">
                            You may now proceed to assign project heads and begin submitting project forms.
                        </div>
                    </div>

                    {{-- RIGHT --}}
                    <div class="flex items-center gap-2">

                        <a href="{{ route('org.projects.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg 
                                bg-emerald-600 px-4 py-2 text-sm font-semibold text-white 
                                hover:bg-emerald-700 transition">

                            Go to Projects

                        </a>

                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-800">
                            Registered
                        </span>

                    </div>

                </div>
            </div>
        @endif





        @if(!$encodeSyId)

            <div class="rounded-xl border border-slate-200 bg-white p-6 text-slate-700">
                Please select a target school year to see and fill out the required forms.
            </div>

        @else



        {{-- Forms Grid --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            @foreach($forms as $key => $f)

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between gap-4">

                    {{-- Label --}}
                    <div>

                        <div class="text-base font-semibold text-slate-900">
                            {{ $f['label'] }}
                        </div>

                    <div class="mt-2 space-y-1">
                        <div class="inline-flex items-center gap-2 text-sm font-medium text-slate-800">

                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                                <span class="h-2.5 w-2.5 rounded-full {{ $f['badge']['dot'] ?? 'bg-slate-400' }}"></span>
                            </span>

                            <span>
                                {{ $f['badge']['text'] }}
                            </span>

                        </div>
                        @if(!empty($f['submitted_at']))
                            <div class="text-xs text-slate-500">
                                Submitted:
                                {{ \Carbon\Carbon::parse($f['submitted_at'])->format('M d, Y — h:i A') }}
                            </div>
                        @endif
                        @if(!empty($f['reviewed_at']))
                            <div class="text-xs text-slate-500">
                                Reviewed:
                                {{ \Carbon\Carbon::parse($f['reviewed_at'])->format('M d, Y — h:i A') }}
                            </div>
                        @endif
                        @if(!empty($f['approved_at']))
                            <div class="text-xs text-emerald-600 font-medium">
                                Approved:
                                {{ \Carbon\Carbon::parse($f['approved_at'])->format('M d, Y — h:i A') }}
                            </div>
                        @endif

                    </div>

                    </div>



                
                    @if($key === 'b6')

                        <div class="text-right space-y-2">

                            @if($constitutionSubmission)

                                {{-- Download button --}}
                                <a href="{{ route('org.rereg.constitution.download', $constitutionSubmission) }}"
                                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                    Download
                                </a>

                                <div class="text-xs text-slate-500 truncate max-w-[200px]">
                                    {{ $constitutionSubmission->original_filename }}
                                </div>

                            @endif


                            {{-- Upload / Replace button --}}
                            <form method="POST"
                                action="{{ route('org.rereg.constitution.upload') }}"
                                enctype="multipart/form-data">

                                @csrf

                                <label class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 cursor-pointer">

                                    {{ $constitutionSubmission ? 'Replace' : 'Upload' }}

                                    <input type="file"
                                        name="file"
                                        accept="application/pdf"
                                        required
                                        class="hidden"
                                        onchange="this.form.submit()">

                                </label>

                            </form>

                        </div>


                    {{-- Normal forms --}}
                    @elseif($key !== 'b5')

                        @if(!empty($f['editRoute']) && Route::has($f['editRoute']))

                            <a href="{{ route($f['editRoute']) }}"
                               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">

                                Open

                            </a>

                        @else

                            <span class="text-xs text-slate-500">
                                No action
                            </span>

                        @endif

                    @endif

                </div>



                {{-- Moderator info --}}
                @if($key === 'b5')

                <div class="mt-4 space-y-3">

                    <div class="text-sm text-slate-600">
                        This form is completed by the assigned moderator.
                    </div>

                    @php $mod = $b5Moderator ?? null; @endphp


                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                        <div class="text-sm font-semibold text-slate-900">
                            Assigned moderator
                        </div>


                        @if($mod && $mod->user)

                            <div class="mt-2 text-sm text-slate-700">
                                <div>
                                    <span class="text-slate-500">Name:</span>
                                    {{ $mod->user->name }}
                                </div>

                                <div>
                                    <span class="text-slate-500">Email:</span>
                                    {{ $mod->user->email }}
                                </div>
                            </div>

                        @else

                            <div class="mt-2 text-sm text-slate-600">
                                No moderator assigned yet.
                            </div>

                        @endif


                        @if(!empty($canAssignModerator) && $canAssignModerator)

                        <div class="mt-3">

                            <a href="{{ route('org.rereg.assign.moderator.edit') }}"
                               class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">

                                {{ ($mod && $mod->user) ? 'Change Moderator' : 'Assign Moderator' }}

                            </a>

                        </div>

                        @endif

                    </div>

                </div>

                @endif


            </div>

            @endforeach

            {{-- Organization Members Card --}}
            <a href="{{ route('org.organization-members.index') }}"
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition group">

                <div class="flex items-start justify-between gap-4">

                    <div>
                        <div class="text-base font-semibold text-slate-900">
                            Organization Members
                        </div>

                        <div class="mt-2 text-sm text-slate-600">
                            Manage and view your organization members for this school year.
                        </div>

                        <div class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-slate-800">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                            </span>

                            <span>
                                Open Members List
                            </span>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 group-hover:bg-slate-50">
                            Open
                        </span>
                    </div>

                </div>

            </a>





        </div>



        {{-- Footer --}}
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between gap-4">

                <div class="text-sm text-slate-600">

                    @if($isActivated)
                        Registration complete.
                    @else
                        All required forms including Organization Constitution must be approved.
                    @endif

                </div>


                @if($isActivated)

                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-800">
                        Registered
                    </span>

                @elseif($allApproved)

                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-800">
                        Ready for Registration
                    </span>

                @else

                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700">
                        Not Yet Complete
                    </span>

                @endif

            </div>

        </div>


        @endif

    </div>
</x-app-layout>