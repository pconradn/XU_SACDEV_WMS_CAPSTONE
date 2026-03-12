@php
$projectsJson = $submission->projects->map(function ($p) {
    return [
        'id' => $p->id,
        'title' => $p->title,
        'category' => $p->category,
        'target_date' => optional($p->target_date)->format('Y-m-d'),
        'implementing_body' => $p->implementing_body,
        'budget' => (float) $p->budget,

        'objectives' => $p->objectives->pluck('text'),
        'beneficiaries' => $p->beneficiaries->pluck('text'),
        'deliverables' => $p->deliverables->pluck('text'),
        'partners' => $p->partners->pluck('text'),
    ];
});
@endphp

<script>

function spAdminReview(projects)
{
    return {

        openApprove: false,
        openReturn: false,

        projects: projects ?? [],

        projModalOpen: false,
        activeProjectId: null,

        openProject(id)
        {
            this.activeProjectId = id;
            this.projModalOpen = true;
        },

        closeProject()
        {
            this.projModalOpen = false;
            this.activeProjectId = null;
        },

        get activeProject()
        {
            return this.projects.find(
                p => String(p.id) === String(this.activeProjectId)
            );
        },

        formatMoney(n)
        {
            return Number(n ?? 0)
                .toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
        }

    };
}

</script>