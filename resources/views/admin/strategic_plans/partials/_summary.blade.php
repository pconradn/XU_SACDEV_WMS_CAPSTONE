        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="text-sm text-slate-600">Org Dev Total</div>
                <div class="text-xl font-semibold text-slate-900 mt-1">{{ number_format((float)$submission->total_org_dev, 2) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="text-sm text-slate-600">Student Services Total</div>
                <div class="text-xl font-semibold text-slate-900 mt-1">{{ number_format((float)$submission->total_student_services, 2) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="text-sm text-slate-600">Community Involvement Total</div>
                <div class="text-xl font-semibold text-slate-900 mt-1">{{ number_format((float)$submission->total_community_involvement, 2) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="text-sm text-slate-600">Overall Total</div>
                <div class="text-xl font-semibold text-slate-900 mt-1">{{ number_format((float)$submission->total_overall, 2) }}</div>
            </div>
        </div>