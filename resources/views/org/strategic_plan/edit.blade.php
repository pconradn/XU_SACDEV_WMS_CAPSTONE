<x-app-layout>

@php
    $isModerator = auth()->user()?->hasRoleInOrg($organization->id, $schoolYear->id, 'moderator') ?? false;
    $isAdmin = auth()->user()?->isSacdevAdmin() ?? false;
    $mode = $isAdmin ? 'admin' : ($isModerator ? 'moderator' : 'org');

    $canSubmitToModerator = $isPresident 
    && !$isApproved 
    && in_array($submission->status, ['draft', 'returned_by_moderator', 'returned_by_sacdev']);
    $canReviewAsModerator = $isModerator && $submission->status === 'submitted_to_moderator';
    $canSubmitToSacdev = $isModerator && $submission->status === 'approved_by_moderator';
    $canAdminAct = $isAdmin;
@endphp

<div x-data="moderatorActions()" x-init="init()" class="space-y-6">

    @include('org.strategic_plan._header', ['submission' => $submission, 'schoolYear' => $schoolYear])


    <div class="space-y-6">

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            @include('org.strategic_plan._identity', [
                'submission' => $submission,
                'canEdit' => $canEdit,
                'mode' => $mode
            ])
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            @include('org.strategic_plan._projects', [
                'submission' => $submission,
                'canEdit' => $canEdit,
                'mode' => $mode
            ])
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            @include('org.strategic_plan._funds', [
                'submission' => $submission,
                'canEdit' => $canEdit,
                'mode' => $mode
            ])
        </div>



        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            @include('org.strategic_plan._submit', [
                'submission' => $submission,
                'submitRoute' => 'org.rereg.b1.submit'
            ])
        </div>


        {{-- MODALS --}}
        @include('org.strategic_plan.partials._modals', [
            'submission' => $submission,
            'canAdminAct' => $canAdminAct
        ])

    </div>



</div>

<script>
function moderatorActions() {
    return {
        openReturn: false,
        openForward: false,
        openApprove: false,
        returnQuill: null,
        openRevert: false,

        init() {
            this.$nextTick(() => {
                if (document.getElementById('returnEditor')) {
                    this.returnQuill = new Quill('#returnEditor', {
                        theme: 'snow',
                        placeholder: 'Write remarks...'
                    });
                }
            });
        },

        submitReturn(e) {
            this.$refs.returnRemarks.value = this.returnQuill.root.innerHTML;
            e.target.submit();
        }
    }
}
</script>

</x-app-layout>