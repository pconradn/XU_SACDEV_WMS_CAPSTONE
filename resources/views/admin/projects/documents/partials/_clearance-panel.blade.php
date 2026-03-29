<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">

    <div class="flex items-start justify-between">

        <div>

            <div class="text-xs uppercase tracking-wide text-slate-500">
                Off-Campus Clearance
            </div>

            <div class="mt-1 font-semibold text-slate-900">

                @if(!$project->requires_clearance)
                    Not Required
                @else
                    Reference:
                    <span class="font-mono text-blue-700">
                        {{ $project->clearance_reference }}
                    </span>
                @endif

            </div>

        </div>

        {{-- ACTION --}}
        <div>

            @if(!$project->requires_clearance)

                <form method="POST"
                      action="{{ route('admin.projects.require-clearance', $project) }}">
                    @csrf

                    <button
                        class="px-4 py-2 text-xs font-semibold rounded-xl bg-amber-600 text-white hover:bg-amber-700">
                        Require Clearance
                    </button>

                </form>

            @endif

        </div>

    </div>


    {{-- STATUS --}}
    @if($project->requires_clearance)

        <div class="mt-4 flex items-center justify-between">

            <div class="text-sm text-slate-600">
                Status:
            </div>

            <div>

                @switch($project->clearance_status)

                    @case('required')
                        <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-700">
                            Waiting for upload
                        </span>
                        @break

                    @case('uploaded')
                        <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">
                            Uploaded
                        </span>
                        @break

                    @case('verified')
                        <span class="px-2 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-700">
                            Verified
                        </span>
                        @break

                    @case('rejected')
                        <span class="px-2 py-1 rounded text-xs font-medium bg-rose-100 text-rose-700">
                            Returned
                        </span>
                        @break

                    @default
                        <span class="text-xs text-slate-400">—</span>

                @endswitch

            </div>

        </div>

    @endif

</div>