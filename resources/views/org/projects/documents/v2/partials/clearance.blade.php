@if($project->requires_clearance)

<div class="bg-white border rounded-2xl p-5 shadow-sm">

    <div class="flex justify-between items-start">

        <div>
            <p class="text-xs uppercase text-slate-500">
                Off-Campus Clearance
            </p>

            <p class="text-sm font-semibold text-slate-800 mt-1">
                Ref: {{ $project->clearance_reference }}
            </p>
        </div>

        <span class="text-xs px-2 py-1 rounded-full
            @if($project->clearance_status === 'verified') bg-emerald-100 text-emerald-700
            @elseif($project->clearance_status === 'uploaded') bg-blue-100 text-blue-700
            @elseif($project->clearance_status === 'rejected') bg-rose-100 text-rose-700
            @else bg-yellow-100 text-yellow-700
            @endif
        ">
            {{ ucfirst($project->clearance_status) }}
        </span>

    </div>

</div>

@endif