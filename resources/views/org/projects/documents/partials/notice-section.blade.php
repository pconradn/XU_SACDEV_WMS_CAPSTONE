<div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm mb-6">

<div class="text-base font-semibold text-slate-900 mb-4">
Notices
</div>

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">

@include('org.projects.documents.partials.notice-card',[
'title'=>'Postponement Notices',
'items'=>$postponements,
'createRoute'=>route('org.projects.documents.postponement.create',$project),
'createAllowed'=>$postponements->where('status','!=','approved_by_sacdev')->count() === 0
])

@include('org.projects.documents.partials.notice-card',[
'title'=>'Cancellation Notices',
'items'=>$cancellations,
'createRoute'=>route('org.projects.documents.cancellation.create',$project),
'createAllowed'=>$cancellations->count() === 0
])

</div>

</div>