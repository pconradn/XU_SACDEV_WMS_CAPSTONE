@php
    $initialProjects = $submission->projects
        ->values()
        ->map(function ($p) {
            return [
                'category' => $p->category,
                'target_date' => optional($p->target_date)->format('Y-m-d'),
                'title' => $p->title,
                'implementing_body' => $p->implementing_body,
                'budget' => (string) $p->budget,

                'objectives' => $p->objectives->pluck('text')->values()->all(),
                'beneficiaries' => $p->beneficiaries->pluck('text')->values()->all(),
                'deliverables' => $p->deliverables->pluck('text')->values()->all(),
                'partners' => $p->partners->pluck('text')->values()->all(),
            ];
        })
        ->all();

    $initialFundSources = $submission->fundSources
        ->values()
        ->map(fn($fs) => [
            'type' => $fs->type,
            'label' => $fs->label,
            'amount' => (string) $fs->amount,
        ])
        ->all();
@endphp

<script>
    window.__SP_INIT__ = {
        projects: @json(old('projects', $initialProjects)),
        fundSources: @json(old('fund_sources', $initialFundSources)),
    };
</script>

<script>
    function strategicPlanForm(init) {

        // ----------------------------
        // Helpers
        // ----------------------------
        const num = (val) => {
            const n = parseFloat(val);
            return isNaN(n) ? 0 : n;
        };

        const hasText = (s) => (s ?? '').toString().trim().length > 0;

        const trimmedCount = (arr) => {
            if (!Array.isArray(arr)) return 0;
            return arr.filter(x => hasText(x)).length;
        };

        // ----------------------------
        // Projects init (from server)
        // ----------------------------
        const incomingProjects = init?.projects ?? [];

        let nextProjectIdx = 0;

        const normalizeProject = (p) => {
            const obj = {
                _idx: (typeof p._idx === 'number') ? p._idx : nextProjectIdx++,
                category: p.category ?? 'org_dev',
                target_date: p.target_date ?? '',
                title: p.title ?? '',
                implementing_body: p.implementing_body ?? '',
                budget: (p.budget ?? '0').toString(),

                objectives: Array.isArray(p.objectives) ? p.objectives : [''],
                beneficiaries: Array.isArray(p.beneficiaries) ? p.beneficiaries : [''],
                deliverables: Array.isArray(p.deliverables) ? p.deliverables : [''],
                partners: Array.isArray(p.partners) ? p.partners : [],
            };

            if (obj.objectives.length === 0) obj.objectives = [''];
            if (obj.beneficiaries.length === 0) obj.beneficiaries = [''];
            if (obj.deliverables.length === 0) obj.deliverables = [''];

            return obj;
        };

        const projects = (incomingProjects || []).map(normalizeProject);
        nextProjectIdx = projects.reduce((m, p) => Math.max(m, p._idx + 1), 0);

        // ----------------------------
        // Funds init (from server)
        // ----------------------------
        const fixedFundTypes = [
            { type: 'org_funds', label: 'Student Org Funds' },
            { type: 'aeco', label: 'AECO Fund (Finance Office)' },
            { type: 'pta', label: 'PTA' },
            { type: 'membership_fee', label: 'Membership Fee' },
            { type: 'raised_funds', label: 'Raised Funds' },
        ];

        const fixedFundAmounts = {
            org_funds: '0',
            aeco: '0',
            pta: '0',
            membership_fee: '0',
            raised_funds: '0',
        };

        let nextFundIdx = 0;
        const fundIndexMap = {};
        const otherSources = [];

        fixedFundTypes.forEach(t => {
            fundIndexMap[t.type] = nextFundIdx++;
        });

        (init?.fundSources ?? []).forEach(fs => {
            const type = (fs.type ?? '').toString();
            const amount = (fs.amount ?? '0').toString();

            if (type in fixedFundAmounts) {
                fixedFundAmounts[type] = amount;
            } else if (type === 'other') {
                otherSources.push({
                    _idx: nextFundIdx++,
                    label: (fs.label ?? '').toString(),
                    amount: amount,
                });
            }
        });

        // ----------------------------
        // Alpine component
        // ----------------------------
        return {

            // ===== categories
            categories: [
                { key: 'org_dev', label: 'Organizational Development' },
                { key: 'student_services', label: 'Student Services' },
                { key: 'community_involvement', label: 'Community Involvement' },
            ],

            // ===== projects state
            nextProjectIdx,
            projects,

            projectsByCategory(catKey) {
                return this.projects.filter(p => p.category === catKey);
            },

            removeProject(idx) {
                this.projects = this.projects.filter(p => p._idx !== idx);
            },

            // ===== project validation + status
            projectStatus(p) {
                return {
                    objectivesOk: trimmedCount(p?.objectives) >= 1,
                    beneficiariesOk: trimmedCount(p?.beneficiaries) >= 1,
                    deliverablesOk: trimmedCount(p?.deliverables) >= 1,
                };
            },

            projectValid(p) {
                return trimmedCount(p?.objectives) >= 1
                    && trimmedCount(p?.beneficiaries) >= 1
                    && trimmedCount(p?.deliverables) >= 1;
            },

            allProjectsValid() {
                if (this.projects.length === 0) return false;
                return this.projects.every(p => this.projectValid(p));
            },

            categoryTotal(catKey) {
                return this.projectsByCategory(catKey).reduce((sum, p) => sum + num(p.budget), 0);
            },

            overallTotal() {
                return this.categories.reduce((sum, c) => sum + this.categoryTotal(c.key), 0);
            },

            formatMoney(n) {
                const x = (typeof n === 'number') ? n : num(n);
                return x.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            // ===== funds state
            fixedFundTypes,
            fixedFundAmounts,
            otherSources,
            fundIndexMap,
            nextFundIdx,

            fundSourceIndex(type) {
                return this.fundIndexMap[type] ?? 0;
            },

            addOtherSource() {
                const idx = this.nextFundIdx++;
                this.otherSources.push({ _idx: idx, label: '', amount: '0' });
            },

            removeOtherSource(idx) {
                this.otherSources = this.otherSources.filter(o => o._idx !== idx);
            },

            totalFunds() {
                let total = 0;
                Object.keys(this.fixedFundAmounts).forEach(k => total += num(this.fixedFundAmounts[k]));
                this.otherSources.forEach(o => total += num(o.amount));
                return total;
            },

            // ======================================================
            // MODAL FLOW (Create/Edit) — create only when complete
            // ======================================================
            detailsOpen: false,
            draftProject: null,
            draftMode: 'create',     // 'create' | 'edit'
            draftEditIdx: null,

            openProjectCreate(category) {
                this.draftMode = 'create';
                this.draftEditIdx = null;

                this.draftProject = {
                    category,
                    target_date: '',
                    title: '',
                    implementing_body: '',
                    budget: '',
                    objectives: [''],
                    beneficiaries: [''],
                    deliverables: [''],
                    partners: [],
                };

                this.detailsOpen = true;
            },

            openProjectEdit(idx) {
                const original = this.projects.find(x => x._idx === idx);
                if (!original) return;

                this.draftMode = 'edit';
                this.draftEditIdx = idx;

                // clone so cancel won’t mutate existing project
                this.draftProject = JSON.parse(JSON.stringify({
                    ...original,
                    objectives: original.objectives ?? [''],
                    beneficiaries: original.beneficiaries ?? [''],
                    deliverables: original.deliverables ?? [''],
                    partners: original.partners ?? [],
                }));

                this.detailsOpen = true;
            },

            closeDetails() {
                this.detailsOpen = false;
                this.draftProject = null;
                this.draftMode = 'create';
                this.draftEditIdx = null;
            },

            isDraftComplete() {
                const p = this.draftProject;
                if (!p) return false;

                const hasList = (arr) => Array.isArray(arr) && arr.some(x => hasText(x));

                return (
                    hasText(p.target_date) &&
                    hasText(p.title) &&
                    // budget can be empty if you want optional, but you said "complete"
                    // so we treat empty as incomplete
                    hasText(p.budget) &&
                    num(p.budget) >= 0 &&
                    hasList(p.objectives) &&
                    hasList(p.beneficiaries) &&
                    hasList(p.deliverables)
                );
            },

            saveDraftProject() {
                if (!this.isDraftComplete()) return;

                if (this.draftMode === 'create') {
                    const newIdx = this.nextProjectIdx++;
                    this.projects.push({
                        _idx: newIdx,
                        ...this.draftProject,
                    });
                } else {
                    const i = this.projects.findIndex(x => x._idx === this.draftEditIdx);
                    if (i !== -1) {
                        this.projects[i] = {
                            _idx: this.draftEditIdx,
                            ...this.draftProject,
                        };
                    }
                }

                this.closeDetails();
            },

            // ===== text list helpers (work for draft + real project)
            addTextItem(project, key) {
                if (!project) return;
                if (!Array.isArray(project[key])) project[key] = [];
                project[key].push('');
            },

            removeTextItem(project, key, j) {
                if (!project || !Array.isArray(project[key])) return;

                project[key].splice(j, 1);

                if ((key === 'objectives' || key === 'beneficiaries' || key === 'deliverables') && project[key].length === 0) {
                    project[key].push('');
                }
            },

          
            init() {
           
            },
        };
    }
</script>
