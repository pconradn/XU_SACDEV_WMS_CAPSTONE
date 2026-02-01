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
        const projects = init?.projects ?? [];
        const fundSources = init?.fundSources ?? [];

        let nextProjectIdx = 0;

        const normalizeProject = (p) => {
            const obj = {
                _idx: (typeof p._idx === 'number') ? p._idx : nextProjectIdx++,
                _open: false,
                category: p.category ?? 'org_dev',
                target_date: p.target_date ?? '',
                title: p.title ?? '',
                implementing_body: p.implementing_body ?? '',
                budget: p.budget ?? '0',

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

        const normalizedProjects = (projects || []).map(normalizeProject);
        nextProjectIdx = normalizedProjects.reduce((m, p) => Math.max(m, p._idx + 1), 0);

        const fixedTypes = [
            { type: 'org_funds', label: 'Student Org Funds' },
            { type: 'aeco', label: 'AECO Fund (Finance Office)' },
            { type: 'pta', label: 'PTA' },
            { type: 'membership_fee', label: 'Membership Fee' },
            { type: 'raised_funds', label: 'Raised Funds' },
        ];

        const fixedFundAmounts = {};
        fixedTypes.forEach(t => fixedFundAmounts[t.type] = '0');

        let nextFundIdx = 0;
        const otherSources = [];

        (fundSources || []).forEach(fs => {
            const type = fs.type ?? '';
            const amount = fs.amount ?? '0';
            if (Object.prototype.hasOwnProperty.call(fixedFundAmounts, type)) {
                fixedFundAmounts[type] = amount;
            } else if (type === 'other') {
                otherSources.push({
                    _idx: nextFundIdx++,
                    label: fs.label ?? '',
                    amount: amount,
                });
            }
        });

        // Reserve indices for fixed fund types so Blade names stay stable
        const fundIndexMap = {};
        fixedTypes.forEach(t => fundIndexMap[t.type] = nextFundIdx++);
        // Ensure other indices don't collide
        otherSources.forEach(o => { o._idx = nextFundIdx++; });

        return {


            fixedFundTypes: [
                { type: 'org_funds', label: 'Student Org Funds' },
                { type: 'aeco', label: 'AECO Fund (Finance Office)' },
                { type: 'pta', label: 'PTA' },
                { type: 'membership_fee', label: 'Membership Fee' },
                { type: 'raised_funds', label: 'Raised Funds' },
            ],

            // map: type => amount string/number
                fixedFundAmounts: {
                org_funds: '0',
                aeco: '0',
                pta: '0',
                membership_fee: '0',
                raised_funds: '0',
            },

            otherSources: [],       // { _idx, label, amount }
            fundIndexMap: {},       // type => index
            nextFundIdx: 0,


            initFundsFromServer() {
            // init.fundSources should be an array like [{type,label,amount}, ...]
            const sources = (init?.fundSources ?? []);

            // build stable indices for fixed types first
            this.fixedFundTypes.forEach(t => {
                if (!(t.type in this.fundIndexMap)) {
                    this.fundIndexMap[t.type] = this.nextFundIdx++;
                }
            });

            // apply incoming values
            sources.forEach(fs => {
                const type = fs.type ?? '';
                const amount = (fs.amount ?? '0').toString();

                if (type in this.fixedFundAmounts) {
                    this.fixedFundAmounts[type] = amount;
                } else if (type === 'other') {
                    const idx = this.nextFundIdx++;
                    this.otherSources.push({
                        _idx: idx,
                        label: (fs.label ?? '').toString(),
                        amount: amount,
                    });
                }
            });
            },

            fundSourceIndex(type) {
                // fixed types only
                return this.fundIndexMap[type] ?? 0;
            },

            addOtherSource() {
                const idx = this.nextFundIdx++;
                this.otherSources.push({ _idx: idx, label: '', amount: '0' });
            },

            removeOtherSource(idx) {
            this.otherSources = this.otherSources.filter(o => o._idx !== idx);
            },

            num(val) {
            const n = parseFloat(val);
            return isNaN(n) ? 0 : n;
            },

            totalFunds() {
            let total = 0;

            // fixed
            Object.keys(this.fixedFundAmounts).forEach(k => {
                total += this.num(this.fixedFundAmounts[k]);
            });

            // other
            this.otherSources.forEach(o => {
                total += this.num(o.amount);
            });

            return total;
            },

            init() {
                this.initFundsFromServer();
            },












            categories: [
                { key: 'org_dev', label: 'Organizational Development' },
                { key: 'student_services', label: 'Student Services' },
                { key: 'community_involvement', label: 'Community Involvement' },
            ],

            nextProjectIdx,
            projects: normalizedProjects,

            normalizeProject(p) {
                const obj = {
                    _idx: (typeof p._idx === 'number') ? p._idx : this.nextProjectIdx++,
                    _open: false,
                    category: p.category ?? 'org_dev',
                    target_date: p.target_date ?? '',
                    title: p.title ?? '',
                    implementing_body: p.implementing_body ?? '',
                    budget: p.budget ?? '0',
                    objectives: Array.isArray(p.objectives) ? p.objectives : [''],
                    beneficiaries: Array.isArray(p.beneficiaries) ? p.beneficiaries : [''],
                    deliverables: Array.isArray(p.deliverables) ? p.deliverables : [''],
                    partners: Array.isArray(p.partners) ? p.partners : [],
                };

                if (obj.objectives.length === 0) obj.objectives = [''];
                if (obj.beneficiaries.length === 0) obj.beneficiaries = [''];
                if (obj.deliverables.length === 0) obj.deliverables = [''];

                return obj;
            },

            projectsByCategory(catKey) {
                return this.projects.filter(p => p.category === catKey);
            },

            addProject(category) {
                this.projects.push(this.normalizeProject({
                    category,
                    objectives: [''],
                    beneficiaries: [''],
                    deliverables: [''],
                    partners: [],
                }));
            },

            removeProject(idx) {
                this.projects = this.projects.filter(p => p._idx !== idx);
            },

            addTextItem(project, key) {
                if (!Array.isArray(project[key])) project[key] = [];
                project[key].push('');
            },

            removeTextItem(project, key, j) {
                project[key].splice(j, 1);
                if ((key === 'objectives' || key === 'beneficiaries' || key === 'deliverables') && project[key].length === 0) {
                    project[key].push('');
                }
            },

            num(val) {
                const n = parseFloat(val);
                return isNaN(n) ? 0 : n;
            },

            categoryTotal(catKey) {
                return this.projectsByCategory(catKey).reduce((sum, p) => sum + this.num(p.budget), 0);
            },

            overallTotal() {
                return this.categories.reduce((sum, c) => sum + this.categoryTotal(c.key), 0);
            },

            formatMoney(n) {
                const x = (typeof n === 'number') ? n : this.num(n);
                return x.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            fundSourceIndex(type) {
                return this.fundIndexMap[type];
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
                Object.keys(this.fixedFundAmounts).forEach(k => total += this.num(this.fixedFundAmounts[k]));
                this.otherSources.forEach(o => total += this.num(o.amount));
                return total;
            },

            // modal state
            detailsOpen: false,
            detailsProjectIdx: null,

            openDetails(idx) {
                this.detailsProjectIdx = idx;
                this.detailsOpen = true;
            },

            closeDetails() {
                this.detailsOpen = false;
                this.detailsProjectIdx = null;
            },

            get detailsProject() {
                return this.projects.find(p => p._idx === this.detailsProjectIdx) || null;
            },

            trimmedCount(arr) {
                if (!Array.isArray(arr)) return 0;
                return arr.filter(x => String(x ?? '').trim().length > 0).length;
            },

            projectValid(p) {
                return this.trimmedCount(p.objectives) >= 1
                    && this.trimmedCount(p.beneficiaries) >= 1
                    && this.trimmedCount(p.deliverables) >= 1;
            },

            projectStatus(p) {
                return {
                    objectivesOk: this.trimmedCount(p.objectives) >= 1,
                    beneficiariesOk: this.trimmedCount(p.beneficiaries) >= 1,
                    deliverablesOk: this.trimmedCount(p.deliverables) >= 1,
            };
            },

            allProjectsValid() {
                // If there are 0 projects total, do you want to allow submit?
                // Usually NO for this form — so return false if none.
                if (this.projects.length === 0) return false;

                return this.projects.every(p => this.projectValid(p));
            },
        };
    }
</script>
