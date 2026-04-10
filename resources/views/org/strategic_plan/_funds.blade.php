<div x-data="{ editing: false, status: '{{ $submission->status }}' }">

    <livewire:rereg.funds-manager 
        :submissionId="$submission->id" 
        :canEdit="$canEdit" 
    />

</div>