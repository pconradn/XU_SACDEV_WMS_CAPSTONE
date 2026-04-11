<livewire:forms.funding-budget-summary
    :budget="$budget ?? null"
    :proposalData="$project->proposalDocument?->proposalData ?? null"
/>

<livewire:forms.budget-items :budget="$budget ?? null" />