<x-app-layout>

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 mb-6">

    <div class="flex flex-col gap-4">

        {{-- TOP --}}
        <div class="flex items-start justify-between gap-4">

            <div>
                <div class="text-[11px] uppercase tracking-wide text-slate-500">
                    SACDEV Admin • Project Review
                </div>

                <h2 class="font-semibold text-xl text-slate-900 mt-1 leading-tight">
                    {{ $header['title'] }}
                </h2>

                <div class="text-sm text-slate-600 mt-1">
                    {{ $header['org'] }} • {{ $header['school_year'] }}
                </div>

                <div class="text-xs text-slate-500 mt-1">
                    Project Head: {{ $header['project_head'] ?? '—' }}
                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex items-start gap-2 shrink-0">

                <a href="{{ route('admin.org.projects.index', [$project->organization_id, $project->school_year_id]) }}"
                   class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 transition">
                    ← Back
                </a>

            </div>

        </div>

        {{-- STATUS ROW --}}
      <div class="flex items-center justify-between border-t border-slate-100 pt-3">

         <div class="flex items-center gap-2">

            {{-- MAIN STATUS --}}
            @php
                  $statusClasses = [
                     'approved' => 'bg-emerald-100 text-emerald-700',
                     'approved_by_sacdev' => 'bg-emerald-100 text-emerald-700',
                     'submitted' => 'bg-blue-100 text-blue-700',
                     'draft' => 'bg-amber-100 text-amber-700',
                  ];
            @endphp

            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$header['status']] ?? 'bg-slate-100 text-slate-700' }}">
                  {{ $header['status_label'] }}
            </span>


            {{-- 🔥 NEW: ADMIN ACTION BADGE --}}
            @if(($pendingForAdmin ?? 0) > 0)
                  <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                     {{ $pendingForAdmin }} awaiting review
                  </span>
            @else
                  <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                     No pending reviews
                  </span>
            @endif

         </div>


         <div class="text-[11px] text-slate-400">
            Last updated: {{ now()->format('M d, Y h:i A') }}
         </div>

      </div>

    </div>

</div>


<div class="py-8">
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">


   <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

   
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 space-y-5">

         {{-- HEADER --}}
         <div class="flex items-start justify-between">

            <h2 class="text-sm font-semibold text-slate-800">
                  Project Snapshot
            </h2>

            <div class="flex items-center gap-2">

                  {{-- STATUS --}}
                  @if($snapshot['status'] === 'submitted')
                     <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                        Proposed
                     </span>
                  @elseif($snapshot['status'] === 'approved_by_sacdev')
                     <span class="px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">
                        Approved
                     </span>
                  @elseif($snapshot['status'] === 'draft')
                     <span class="px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-700">
                        Draft
                     </span>
                  @endif

                  {{-- OFF CAMPUS --}}
                  @if($snapshot['is_off_campus'])
                     <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                        Off-Campus
                     </span>
                  @endif

            </div>

         </div>


         {{-- DESCRIPTION --}}
         @if(!empty($snapshot['description']))
            <div class="text-sm text-slate-600 leading-relaxed">
                  {{ $snapshot['description'] }}
            </div>
         @endif


         {{-- MAIN DETAILS --}}
         <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">

            <div>
                  <p class="text-slate-500">Date</p>
                  <p class="font-medium text-slate-800">
                     {{ $snapshot['date'] ?? '—' }}
                  </p>
            </div>

            <div>
                  <p class="text-slate-500">Time</p>
                  <p class="font-medium text-slate-800">
                     {{ $snapshot['time'] ?? '—' }}
                  </p>
            </div>

            <div>
                  <p class="text-slate-500">Venue</p>
                  <p class="font-medium text-slate-800">
                     {{ $snapshot['venue'] ?? '—' }}
                  </p>
            </div>

         </div>


      {{-- FINANCIAL SNAPSHOT --}}
      <div class="grid grid-cols-2 gap-4 text-sm pt-3 border-t border-slate-100">

         {{-- TOTAL BUDGET --}}
         <div>
               <p class="text-slate-500">Total Budget</p>
               <p class="font-semibold text-slate-900">
                  @if($snapshot['total_budget'])
                     ₱ {{ number_format($snapshot['total_budget'], 2) }}
                  @else
                     —
                  @endif
               </p>
         </div>

         {{-- FUND SOURCE --}}
         <div>
            <p class="text-slate-500">Fund Sources</p>

            @if($snapshot['fund_sources'] && $snapshot['fund_sources']->count())

               <div class="mt-1 space-y-1">

                     @foreach($snapshot['fund_sources'] as $source)

                        <div class="flex items-center justify-between text-xs bg-slate-50 border border-slate-200 rounded-lg px-2 py-1">

                           <span class="text-slate-700">
                                 {{ $source->source_name ?? 'Source' }}
                           </span>

                           @if(isset($source->amount))
                                 <span class="font-medium text-slate-900">
                                    ₱ {{ number_format($source->amount, 2) }}
                                 </span>
                           @endif

                        </div>

                     @endforeach

               </div>

            @else

               <p class="font-medium text-slate-800">
                     —
               </p>

            @endif
         </div>

      </div>

   </div>

         <div class="rounded-2xl border border-indigo-200 bg-indigo-50/40 shadow-sm p-6">

            {{-- HEADER --}}
            <div class="flex items-start justify-between">

               <div>
                     <h3 class="text-sm font-semibold text-indigo-900">
                        Pre-Implementation Review
                     </h3>

                     <p class="text-xs text-indigo-800/70 mt-1">
                        Project Proposal & Budget must be reviewed before proceeding
                     </p>
               </div>

               {{-- SUBTLE TAG --}}
               <span class="text-[10px] font-semibold px-2 py-1 rounded-full bg-white text-indigo-700 border border-indigo-200">
                     Required
               </span>

            </div>


            <div class="mt-4 space-y-3">

               @if($combined['exists'])

                     {{-- PROPOSAL STATUS --}}
                     @if($proposalDoc)

                        @php
                           $pending = $proposalDoc->signatures
                                 ?->where('status','pending')
                                 ->sortBy('id')
                                 ->first();
                        @endphp

                        <div class="rounded-xl border border-indigo-200 bg-white p-4">

                           {{-- TOP --}}
                           <div class="flex items-center justify-between">

                                 <div class="text-xs font-semibold text-slate-800">
                                    Project Proposal
                                 </div>

                                 <span class="px-2 py-1 text-[10px] font-medium rounded {{ $proposalDoc->status_badge_class }}">
                                    {{ $proposalDoc->status_label }}
                                 </span>

                           </div>

                           {{-- PENDING --}}
                           @if($pending)
                                 <div class="mt-2 text-xs text-slate-600">

                                    <span class="text-slate-500">Awaiting</span>
                                    <span class="font-semibold text-slate-800">
                                       {{ ucfirst(str_replace('_',' ', $pending->role)) }}
                                    </span>

                                    @if($pending->user)
                                       <div class="text-slate-400 mt-0.5">
                                             {{ $pending->user->name }}
                                       </div>
                                    @endif

                                 </div>
                           @endif

                        </div>

                     @endif


                     {{-- MAIN ACTION --}}
                     <a href="{{ $combined['view_url'] }}"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold 
                              text-indigo-900 bg-white border border-indigo-200 hover:bg-indigo-100/60 transition">

                        <span>Open Combined Proposal</span>
                        <span class="text-xs text-indigo-600">→</span>

                     </a>


                     {{-- PRINTS --}}
                     <div class="flex gap-2">

                        @if($combined['proposal_print_url'])
                           <a href="{{ $combined['proposal_print_url'] }}"
                              target="_blank"
                              class="flex-1 text-center px-3 py-2 text-xs font-medium rounded-lg 
                                       text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 transition">
                                 Print Proposal
                           </a>
                        @endif

                        @if($combined['budget_print_url'])
                           <a href="{{ $combined['budget_print_url'] }}"
                              target="_blank"
                              class="flex-1 text-center px-3 py-2 text-xs font-medium rounded-lg 
                                       text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 transition">
                                 Print Budget
                           </a>
                        @endif

                     </div>


                     {{-- PACKETS --}}
                     <div class="pt-2 border-t border-indigo-200">

                        <a href="{{ route('admin.projects.packets.index', $project) }}"
                           class="w-full flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg 
                                 text-indigo-700 bg-white border border-indigo-200 hover:bg-indigo-100 transition">
                           View Physical Submission Packets
                        </a>

                     </div>

               @else

                     <div class="text-xs text-indigo-800/70 text-center py-4">
                        No proposal submitted yet.
                     </div>

               @endif

            </div>

         </div>

    </div>


    @include('admin.projects.documents.partials._clearance-panel')
    @include('admin.projects.partials._clearance-actions')


    @include('admin.projects.documents.partials._documents-table')


    @include('admin.projects.documents.partials._notices-table')

    @include('admin.projects.documents.partials._admin-actions')


</div>
</div>

</x-app-layout>