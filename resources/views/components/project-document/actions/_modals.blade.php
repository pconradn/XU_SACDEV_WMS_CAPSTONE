

@php
    $formCode = $document?->formType?->code;
@endphp

@php
    $isFeesCollection = ($document->formType->code ?? null) === 'FEES_COLLECTION_REPORT';
    $isSelling = ($document->formType->code ?? null) === 'SELLING_APPLICATION';
    $isAdmin = auth()->user()->system_role === 'sacdev_admin';
    $isSolicitation = $formCode === 'SOLICITATION_APPLICATION';
    $isOffCampus = $formCode === 'OFF_CAMPUS_APPLICATION';
   


    //dd($user)
@endphp


@php

    
    $user = auth()->user();


    $membership = $user->orgMemberships
        ->where('organization_id', session('active_org_id'))
        ->where('school_year_id', session('encode_sy_id'))
        ->whereNull('archived_at')
        ->first();
    $role = $membership->role ?? null;
    
    $isPresident = $role === 'president';
    $isModerator = $role === 'moderator';
    $isTreasurer = $role === 'treasurer';
    $isFinance = $role === 'finance_officer';

@endphp

@php
$formRouteMap = [
    'PROJECT_PROPOSAL' => 'project-proposal',
    'BUDGET_PROPOSAL' => 'budget-proposal',
    'SOLICITATION_APPLICATION' => 'solicitation',
    'SELLING_APPLICATION' => 'selling',
    'REQUEST_TO_PURCHASE' => 'request-to-purchase',
    'FEES_COLLECTION_REPORT' => 'fees-collection',
    'SELLING_ACTIVITY_REPORT' => 'selling-activity-report',
    'SOLICITATION_SPONSORSHIP_REPORT' => 'solicitation-sponsorship-report',
    'TICKET_SELLING_REPORT' => 'ticket-selling-report',
    'DOCUMENTATION_REPORT' => 'documentation-report',
    'LIQUIDATION_REPORT' => 'liquidation-report',
    'OFF_CAMPUS_APPLICATION' => 'off-campus',
];

$routePrefix = $formRouteMap[$document?->formType?->code] ?? null;
@endphp


{{-- ================= MODAL STYLES ================= --}}
<style>
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(2px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .modal {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 420px;
        padding: 22px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        animation: fadeIn .2s ease;
    }
    @keyframes fadeIn {
        from {opacity:0; transform:translateY(10px);}
        to {opacity:1; transform:translateY(0);}
    }
</style>


{{-- ================= CORE MODAL FUNCTIONS ================= --}}
<script>
    function openModal(id){ document.getElementById(id).style.display = 'flex'; }
    function closeModal(id){ document.getElementById(id).style.display = 'none'; }

    function submitMainForm(action = 'submit') {
        const form = document.getElementById('proposalForm');
        if (!form) return;

        const actionInput = document.getElementById('formAction');
        if (actionInput) actionInput.value = action;

        form.submit();
    }

    // prevent double click submit
    function lockButton(btn){
        btn.disabled = true;
        btn.innerText = 'Processing...';
    }
</script>


{{-- ================= SUBMIT ================= --}}
@if($isSolicitation && $isProjectHead)

    {{-- ================= SOLICITATION AGREEMENT MODAL ================= --}}
    <div id="submitModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">

            {{-- HEADER --}}
            <div class="border-b px-5 py-3">
                <h2 class="text-sm font-semibold text-slate-900">
                    Solicitation Agreement
                </h2>
            </div>

            {{-- BODY --}}
            <div class="px-5 py-4 space-y-3">

                <p class="text-sm text-slate-700 leading-relaxed">
                    We understand that there are rules and regulations which govern
                    solicitation activities using the name of the University.
                </p>

                <p class="text-sm text-slate-600 leading-relaxed">
                    Failure to abide by them and the approved terms and conditions of
                    this form entails sanctions for the organization and disciplinary
                    measures for the students involved.
                </p>

                {{-- OPTIONAL HIGHLIGHT --}}
                <div class="mt-3 border border-amber-200 bg-amber-50 rounded-lg p-3 text-xs text-amber-800">
                    By proceeding, you confirm that all information submitted is accurate
                    and compliant with university policies.
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="border-t px-5 py-3 flex justify-end gap-2">

                <button type="button"
                    onclick="closeModal('submitModal')"
                    class="px-4 py-2 text-xs border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-100">
                    Cancel
                </button>

                <button type="submit"
                    form="proposalForm"
                    name="action"
                    value="submit"
                    class="px-4 py-2 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Agree and Submit
                </button>

            </div>

        </div>

    </div>

@elseif($isOffCampus && $isProjectHead)

<div id="submitModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">

        {{-- HEADER --}}
        <div class="border-b px-5 py-3">
            <h2 class="text-sm font-semibold text-slate-900">
                Off-Campus Activity Submission Guidelines
            </h2>
        </div>

        {{-- BODY --}}
        <div class="px-5 py-4 space-y-3 text-sm text-slate-700 leading-relaxed">

            <p>
                This form corresponds to the <span class="font-semibold">Off-Campus Activity Form (Form A12)</span>.
                Please ensure that all required information is accurately encoded before submission.
            </p>

            <p>
                This form must be submitted to the <span class="font-semibold">Student Activities and Leadership Development (SACDEV - OSA)</span>
                at least <span class="font-semibold">3 days before the scheduled activity</span>.
            </p>

            <p>
                After submission, you will be able to generate the 
                <span class="font-semibold">Student Travel Agreement (Form A12.1)</span> 
                from the <span class="font-semibold">Project Document Hub</span>.
                This document must be printed, accomplished by all participants, and attached to this form.
            </p>

            <p>
                Only students who have completed and submitted the Student Travel Agreement shall be included
                in the official participant list and allowed to join the activity.
            </p>

            <p>
                All off-campus activities must be accompanied by the organization moderator or an officially assigned
                representative who is a <span class="font-semibold">full-time faculty or staff member</span> of the University.
            </p>

            <p>
                Any changes to the approved schedule or itinerary must be immediately communicated to SACDEV.
            </p>

            {{-- RESPONSIBILITY STATEMENT --}}
            <div class="mt-3 border border-blue-200 bg-blue-50 rounded-lg p-3 text-xs text-blue-800 leading-relaxed">
                We, cognizant of the risks and benefits entailed by our activity, take upon ourselves the responsibility of ensuring the welfare and safety of everyone participating in this off-campus activity.
            </div>

            {{-- HIGHLIGHT --}}
            <div class="mt-2 border border-amber-200 bg-amber-50 rounded-lg p-3 text-xs text-amber-800">
                By proceeding, you confirm that all submitted information is accurate, and you agree to comply with
                university policies and off-campus activity guidelines.
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="border-t px-5 py-3 flex justify-end gap-2">

            <button type="button"
                    onclick="closeModal('submitModal')"
                    class="px-4 py-2 text-xs border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-100">
                Cancel
            </button>

            <button type="submit"
                    form="proposalForm"
                    name="action"
                    value="submit"
                    class="px-4 py-2 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Agree and Submit
            </button>

        </div>

    </div>

</div>

@else

    {{-- ================= DEFAULT SUBMIT MODAL ================= --}}
    <div id="submitModal" class="modal-overlay">

        <div class="modal">

            {{-- ================= AGREEMENT STEP (ONLY IF COMBINED) ================= --}}
            @if($isCombined ?? false)

                <div id="agreementStep">

                    <h3 class="text-sm font-semibold mb-2">
                        Student Agreement
                    </h3>

                    <p class="text-xs text-slate-600 mb-3">
                        Please review the agreement before submitting this combined proposal.
                    </p>

                    <div class="text-xs text-slate-700 space-y-2 max-h-[200px] overflow-y-auto pr-2">

                        <p><strong>1. Responsibilities</strong></p>
                        <p>
                            I acknowledge that I am responsible for submitting all post-documentation requirements.
                        </p>

                        <p><strong>2. Consequences</strong></p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Inability to take examinations</li>
                            <li>Inability to obtain official documents</li>
                            <li>Restrictions on future projects</li>
                        </ul>

                        <p><strong>3. Commitment</strong></p>
                        <p>
                            I commit to fulfilling all requirements on time.
                        </p>

                        <div class="border border-amber-200 bg-amber-50 rounded-lg p-2 text-[11px] text-amber-800">
                            ⚠ This submission will be officially recorded.
                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-4 flex justify-between items-center">

                        <button type="button"
                            onclick="closeModal('submitModal')"
                            class="px-3 py-1.5 text-xs border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-100">
                            Cancel
                        </button>

                        <button type="button"
                            id="agreementProceedBtn"
                            class="px-3 py-1.5 text-xs bg-slate-400 text-white rounded-lg cursor-not-allowed"
                            disabled>
                            Please wait 4s...
                        </button>

                    </div>

                </div>

            @endif


            {{-- ================= ORIGINAL SUBMIT CONFIRM ================= --}}
            <div id="confirmStep" class="{{ ($isCombined ?? false) ? 'hidden' : '' }}">

                <h3 class="text-sm font-semibold mb-2">Submit Document</h3>

                <p class="text-xs text-slate-600 mb-4">
                    This will send the document into the approval workflow.
                    You will not be able to edit unless it is returned or edit access is granted, once approved by SACDEV.
                </p>

                <button type="button"
                    onclick="submitMainForm('submit')"
                    class="w-full mb-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-xs">
                    Confirm Submit
                </button>

                <button type="button"
                    onclick="closeModal('submitModal')"
                    class="w-full text-xs text-slate-500">
                    Cancel
                </button>

            </div>

        </div>

    </div>


    {{-- ================= SCRIPT (SAFE, LOCAL ONLY) ================= --}}
    @if($isCombined ?? false)
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const btn = document.getElementById('agreementProceedBtn');
            const agreement = document.getElementById('agreementStep');
            const confirm = document.getElementById('confirmStep');

            if (!btn) return;

            let countdown = 4;

            const interval = setInterval(() => {
                countdown--;

                if (countdown > 0) {
                    btn.innerText = `Please wait ${countdown}s...`;
                } else {
                    clearInterval(interval);

                    btn.disabled = false;
                    btn.classList.remove('bg-slate-400', 'cursor-not-allowed');
                    btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
                    btn.innerText = 'Proceed';
                }
            }, 1000);

            btn.addEventListener('click', function () {
                agreement.classList.add('hidden');
                confirm.classList.remove('hidden');
            });

        });
    </script>
    @endif

@endif

{{-- ================= RESUBMIT ================= --}}
<div id="resubmitModal" class="modal-overlay">
    <div class="modal">
        <h3 class="text-sm font-semibold mb-2 text-amber-600">Resubmit Document</h3>

        <p class="text-xs text-slate-600 mb-4">
            This will reset all previous approvals and restart the workflow.
        </p>

        <button type="button"
            onclick="submitMainForm('submit')"
            class="w-full mb-2 px-4 py-2 bg-amber-500 text-white rounded-lg text-xs">
            Confirm Resubmit
        </button>

        <button type="button" onclick="closeModal('resubmitModal')" class="w-full text-xs text-slate-500">
            Cancel
        </button>
    </div>
</div>




{{-- ================= RETURN ================= --}}
<div id="returnModal" class="modal-overlay">
    <div class="modal">
        <h3 class="text-sm font-semibold mb-2 text-red-600">Return Document</h3>

        <p class="text-xs text-slate-600 mb-3">
            This will send the document back to the project head for revision.
        </p>

        <p class="text-xs text-red-500 mb-4">
            ⚠ This action cannot be undone and may affect the workflow.
        </p>

        @if(!empty($isCombined))

        <form method="POST"
            action="{{ $isAdmin
                ? route('admin.projects.documents.combined-proposal.return', $project)
                : route('org.projects.documents.combined-proposal.return', $project) }}"
            onsubmit="lockButton(this.querySelector('button[type=submit]'))">

            @csrf

            <textarea name="remarks"
                class="w-full border border-slate-300 rounded-lg text-xs p-2 mb-3"
                placeholder="Enter remarks..."
                required></textarea>

            <button class="w-full mb-2 px-4 py-2 bg-red-600 text-white rounded-lg text-xs">
                Return Document
            </button>
        </form>

        @elseif(($isAdmin && $formCode) || $routePrefix)

        <form method="POST"
            action="{{ $isAdmin
                ? route('admin.projects.documents.return', [$project, $formCode])
                : route("org.projects.documents.$routePrefix.return", $project) }}"
            onsubmit="lockButton(this.querySelector('button[type=submit]'))">

            @csrf

            <textarea name="remarks"
                class="w-full border border-slate-300 rounded-lg text-xs p-2 mb-3"
                placeholder="Enter remarks..."
                required></textarea>

            <button class="w-full mb-2 px-4 py-2 bg-red-600 text-white rounded-lg text-xs">
                Return Document
            </button>
        </form>

        @else
        <div class="text-xs text-red-500 text-center py-2">
            ⚠ Unable to determine return route.
        </div>
        @endif

        <button type="button" onclick="closeModal('returnModal')" class="w-full text-xs text-slate-500">
            Cancel
        </button>
    </div>
</div>


{{-- ================= RETRACT ================= --}}
<div id="retractModal" class="modal-overlay">
    <div class="modal">
        <h3 class="text-sm font-semibold mb-2 text-red-600">Retract Approval</h3>

        <p class="text-xs text-slate-600 mb-2">
            You are about to retract your approval.
        </p>

        <p class="text-xs text-red-500 mb-4">
            ⚠ This action cannot be undone and may impact document integrity.
        </p>

        
        @if(!empty($isCombined))

            <form method="POST"
                action="{{ $isAdmin
                    ? route('admin.projects.documents.combined-proposal.retract', $project)
                    : route('org.projects.documents.combined-proposal.retract', $project) }}"
                onsubmit="lockButton(this.querySelector('button[type=submit]'))">

                @csrf

                <button class="w-full mb-2 px-4 py-2 bg-red-600 text-white rounded-lg text-xs">
                    Confirm Retract
                </button>
            </form>

        
        @elseif(($isAdmin && $formCode) || $routePrefix)

            <form method="POST"
                action="{{ $isAdmin
                    ? route('admin.projects.documents.retract', [$project, $formCode])
                    : route('org.projects.documents.retract', [$project, $formCode]) }}"
                onsubmit="lockButton(this.querySelector('button[type=submit]'))">

                @csrf

                <button class="w-full mb-2 px-4 py-2 bg-red-600 text-white rounded-lg text-xs">
                    Confirm Retract
                </button>
            </form>

        
        @else
            <div class="text-xs text-red-500 text-center py-2">
                ⚠ Unable to determine retract route.
            </div>
        @endif

        <button type="button"
            onclick="closeModal('retractModal')"
            class="w-full text-xs text-slate-500">
            Cancel
        </button>
    </div>
</div>

{{-- ================= REQUEST EDIT ================= --}}
<div id="requestEditModal" class="modal-overlay">
    <div class="modal">
        <h3 class="text-sm font-semibold mb-2 text-amber-600">Request Edit</h3>

        <p class="text-xs text-slate-600 mb-3">
            This will request permission to edit this document after approval.
        </p>

        @if(!empty($isCombined))

        <form method="POST"
            action="{{ route('org.projects.documents.combined-proposal.request-edit', $project) }}"
            onsubmit="lockButton(this.querySelector('button[type=submit]'))">

            @csrf

            <textarea name="remarks"
                class="w-full border border-slate-300 rounded-lg text-xs p-2 mb-3"
                placeholder="Optional reason..."></textarea>

            <button class="w-full mb-2 px-4 py-2 bg-amber-500 text-white rounded-lg text-xs">
                Request Edit (Combined)
            </button>
        </form>

        @elseif($formCode)

        <form method="POST"
            action="{{ route('org.projects.documents.request-edit', [$project, $formCode]) }}"
            onsubmit="lockButton(this.querySelector('button[type=submit]'))">

            @csrf

            <textarea name="remarks"
                class="w-full border border-slate-300 rounded-lg text-xs p-2 mb-3"
                placeholder="Optional reason..."></textarea>

            <button class="w-full mb-2 px-4 py-2 bg-amber-500 text-white rounded-lg text-xs">
                Submit Request
            </button>
        </form>

        @else
        <div class="text-xs text-red-500 text-center py-2">
            ⚠ Missing form type.
        </div>
        @endif

        <button type="button" onclick="closeModal('requestEditModal')" class="w-full text-xs text-slate-500">
            Cancel
        </button>
    </div>
</div>


{{-- ================= ADMIN ALLOW EDIT ================= --}}
@if($isAdmin)
<div id="allowEditModal" class="modal-overlay">
    <div class="modal">
        <h3 class="text-sm font-semibold mb-2 text-amber-600">Allow Edit</h3>

        <p class="text-xs text-slate-600 mb-4">
            This will allow the project head to edit and resubmit this document.
        </p>

        @if(!empty($isCombined))

        <form method="POST"
            action="{{ route('admin.projects.documents.combined-proposal.allow-edit', $project) }}"
            onsubmit="lockButton(this.querySelector('button[type=submit]'))">

            @csrf

            <button class="w-full mb-2 px-4 py-2 bg-amber-500 text-white rounded-lg text-xs">
                Allow Edit (Combined)
            </button>
        </form>

        @elseif($formCode)

        <form method="POST"
            action="{{ route('admin.projects.documents.allow-edit', [$project, $formCode]) }}"
            onsubmit="lockButton(this.querySelector('button[type=submit]'))">

            @csrf

            <button class="w-full mb-2 px-4 py-2 bg-amber-500 text-white rounded-lg text-xs">
                Allow Edit
            </button>
        </form>

        @else
        <div class="text-xs text-red-500 text-center py-2">
            ⚠ Missing form type.
        </div>
        @endif

        <button type="button" onclick="closeModal('allowEditModal')" class="w-full text-xs text-slate-500">
            Cancel
        </button>
    </div>
</div>
@endif


{{-- ================= SUBMIT REVISION ================= --}}
<div id="submitRevisionModal" class="modal-overlay">
    <div class="modal">
        <h3 class="text-sm font-semibold mb-2 text-blue-600">Submit Revisions</h3>

        <p class="text-xs text-slate-600 mb-4">
            Your changes will be submitted directly for approval.
        </p>

        <button type="button"
            onclick="submitMainForm('submit')"
            class="w-full mb-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-xs">
            Submit Revisions
        </button>

        <button type="button" onclick="closeModal('submitRevisionModal')" class="w-full text-xs text-slate-500">
            Cancel
        </button>
    </div>
</div>





@if($isOffCampus && ($isPresident || $isModerator || $isAdmin))

<div id="approveModal" class="modal-overlay">
    <div class="modal">

        <h3 class="text-sm font-semibold mb-2 text-green-600">
            Approve Document
        </h3>

        <p class="text-xs text-slate-600 mb-3">
            This action will approve the document and move it to the next approver.
            You can only retract this action if the next approver is still pending.
        </p>

        {{-- ROLE-BASED AGREEMENT --}}
        @if($isPresident)
            <div class="mb-4 border border-blue-200 bg-blue-50 rounded-lg p-3 text-xs text-blue-800 leading-relaxed">
                We, cognizant of the risks and benefits entailed by our activity, take upon ourselves the responsibility of ensuring the welfare and safety of everyone participating in our off-campus activity.
            </div>
        @elseif($isModerator)
            <div class="mb-4 border border-indigo-200 bg-indigo-50 rounded-lg p-3 text-xs text-indigo-800 leading-relaxed">
                I hereby agree to accompany the above-mentioned students in their off-campus activity. As moderator / duly designated representative of the University, I acknowledge that I have read and understood the responsibilities of the accompanying Moderator and assume full responsibility for the proceedings of the activity.
            </div>
        @elseif($isAdmin)
            <div class="mb-4 border border-emerald-200 bg-emerald-50 rounded-lg p-3 text-xs text-emerald-800 leading-relaxed">
                This is to certify that the aforementioned organization has successfully complied with all the requirements set for the aforementioned off-campus activity.
            </div>
        @endif


        @if(!empty($isCombined))

            <form method="POST"
                  onsubmit="lockButton(this.querySelector('button[type=submit]'))"
                  action="{{ $isAdmin
                        ? route('admin.projects.documents.combined-proposal.approve', $project)
                        : route('org.projects.documents.combined-proposal.approve', $project) }}">

                @csrf

                <button class="w-full mb-2 px-4 py-2 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700">
                    Confirm Approval
                </button>

            </form>

        @elseif($routePrefix || $isAdmin)

            <form method="POST"
                  onsubmit="lockButton(this.querySelector('button[type=submit]'))"
                  action="{{ $isAdmin
                        ? route('admin.projects.documents.approve', [$project, $formCode])
                        : route("org.projects.documents.$routePrefix.approve", $project) }}">

                @csrf

                <button class="w-full mb-2 px-4 py-2 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700">
                    Confirm Approval
                </button>

            </form>

        @else
            <div class="text-xs text-red-500">
                Invalid document route configuration.
            </div>
        @endif

        <button type="button"
                onclick="closeModal('approveModal')"
                class="w-full text-xs text-slate-500 mt-1">
            Cancel
        </button>

    </div>
</div>




@elseif($isFeesCollection && $isAdmin)

    {{-- ================= CUSTOM FEES COLLECTION APPROVAL ================= --}}
    <div id="approveModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">

            <div class="border-b px-4 py-3 font-semibold text-sm">
                Review Collection Entries & Add Remarks
            </div>

            <form method="POST"
                action="{{ route('admin.projects.documents.approve', [$project, $document->formType->code]) }}">

                @csrf

                <div class="p-4 overflow-y-auto max-h-[60vh]">

                    <table class="min-w-full text-[12px] border border-slate-300">

                        <thead class="bg-slate-100">
                            <tr>
                                <th class="border px-2 py-1">Number of Payers</th>
                                <th class="border px-2 py-1">Amount Paid</th>
                                <th class="border px-2 py-1">Receipt / Control No.</th>
                                <th class="border px-2 py-1">Remarks (SACDEV)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td class="border px-2 py-1 bg-slate-50">
                                        {{ $item->number_of_payers }}
                                    </td>

                                    <td class="border px-2 py-1 bg-slate-50">
                                        {{ number_format($item->amount_paid, 2) }}
                                    </td>

                                    <td class="border px-2 py-1 bg-slate-50">
                                        {{ $item->receipt_series }}
                                    </td>

                                    <td class="border px-2 py-1">
                                        <input
                                            type="text"
                                            name="items[{{ $item->id }}][remarks]"
                                            value="{{ $item->remarks }}"
                                            class="w-full border border-slate-300 px-2 py-1 text-[12px]">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

                <div class="border-t px-4 py-3 flex justify-end gap-2">

                    <button type="button"
                        onclick="closeModal('approveModal')"
                        class="border border-slate-300 px-4 py-2 text-[12px]">
                        Cancel
                    </button>

                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 text-[12px] hover:bg-emerald-700">
                        Confirm Approval
                    </button>

                </div>

            </form>

        </div>
    </div>

@elseif($isAdmin && $isSelling)

    {{-- ================= SELLING APPLICATION MODAL ================= --}}
    <div id="approveModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">

            <div class="border-b px-4 py-3 font-semibold text-sm">
                Review Goods & Add Remarks
            </div>

            <form method="POST"
                action="{{ route('admin.projects.documents.approve', [$project, $formCode]) }}">

                @csrf

                <div class="p-4 overflow-y-auto max-h-[60vh]">

                    <table class="min-w-full text-[12px] border border-slate-300">

                        <thead class="bg-slate-100">
                            <tr>
                                <th class="border px-2 py-1">Quantity</th>
                                <th class="border px-2 py-1">Particulars</th>
                                <th class="border px-2 py-1">Selling Price</th>
                                <th class="border px-2 py-1">Subtotal</th>
                                <th class="border px-2 py-1">Remarks (SACDEV)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td class="border px-2 py-1 bg-slate-50">
                                        {{ $item->quantity }}
                                    </td>

                                    <td class="border px-2 py-1 bg-slate-50">
                                        {{ $item->particulars }}
                                    </td>

                                    <td class="border px-2 py-1 bg-slate-50">
                                        {{ number_format($item->selling_price, 2) }}
                                    </td>

                                    <td class="border px-2 py-1 bg-slate-50">
                                        {{ number_format($item->quantity * $item->selling_price, 2) }}
                                    </td>

                                    <td class="border px-2 py-1">
                                        <input
                                            type="text"
                                            name="items[{{ $item->id }}][remarks]"
                                            value="{{ $item->remarks }}"
                                            class="w-full border border-slate-300 px-2 py-1 text-[12px]">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

                <div class="border-t px-4 py-3 flex justify-end gap-2">

                    <button type="button"
                        onclick="closeModal('approveModal')"
                        class="border border-slate-300 px-4 py-2 text-[12px]">
                        Cancel
                    </button>

                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 text-[12px] hover:bg-emerald-700">
                        Confirm Approval
                    </button>

                </div>

            </form>

        </div>
    </div>


@elseif($isAdmin && $isSolicitation)

    {{-- ================= SOLICITATION APPROVAL MODAL ================= --}}
    <div id="approveModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white rounded-lg shadow-lg w-full max-w-xl">

            {{-- HEADER --}}
            <div class="border-b px-4 py-3 font-semibold text-sm">
                Assign Solicitation Letter Control Numbers
            </div>

            <form method="POST"
                action="{{ route('admin.projects.documents.approve', [$project, $formCode]) }}"
                onsubmit="lockButton(this.querySelector('button[type=submit]'))">

                @csrf

                <div class="p-4 space-y-4">

                    {{-- APPROVED COUNT --}}
                    <div>
                        <label class="text-[12px] font-medium">
                            Approved Number of Letters
                        </label>

                        <input
                            type="number"
                            name="approved_letter_count"
                            required
                            class="w-full border border-slate-300 px-3 py-2 text-[12px] rounded">
                    </div>

                    {{-- CONTROL SERIES --}}
                    <div class="grid grid-cols-2 gap-3">

                        <div>
                            <label class="text-[12px] font-medium">
                                Control Series Start
                            </label>

                            <input
                                type="text"
                                name="control_series_start"
                                required
                                class="w-full border border-slate-300 px-3 py-2 text-[12px] rounded">
                        </div>

                        <div>
                            <label class="text-[12px] font-medium">
                                Control Series End
                            </label>

                            <input
                                type="text"
                                name="control_series_end"
                                required
                                class="w-full border border-slate-300 px-3 py-2 text-[12px] rounded">
                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="border-t px-4 py-3 flex justify-end gap-2">

                    <button
                        type="button"
                        onclick="closeModal('approveModal')"
                        class="border border-slate-300 px-4 py-2 text-[12px] rounded hover:bg-slate-100">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 text-[12px] rounded hover:bg-emerald-700">
                        Approve
                    </button>

                </div>

            </form>

        </div>
    </div>



@else

    {{-- ================= DEFAULT APPROVE MODAL ================= --}}
    <div id="approveModal" class="modal-overlay">
        <div class="modal">
            <h3 class="text-sm font-semibold mb-2 text-green-600">Approve Document</h3>

            <p class="text-xs text-slate-600 mb-4">
                This action will approve the document and move it to the next approver. You can only retract this action if the next approver is still pending.
            </p>

            @if(!empty($isCombined))

            <form method="POST"
                onsubmit="lockButton(this.querySelector('button[type=submit]'))"
                action="{{ $isAdmin
                    ? route('admin.projects.documents.combined-proposal.approve', $project)
                    : route('org.projects.documents.combined-proposal.approve', $project) }}">

                @csrf

                <button class="w-full mb-2 px-4 py-2 bg-green-600 text-white rounded-lg text-xs">
                    Confirm Approval
                </button>

            </form>

            @elseif($routePrefix || $isAdmin)

                <form method="POST"
                    onsubmit="lockButton(this.querySelector('button[type=submit]'))"
                    action="{{ $isAdmin
                        ? route('admin.projects.documents.approve', [$project, $formCode])
                        : route("org.projects.documents.$routePrefix.approve", $project) }}">

                    @csrf

                    <button class="w-full mb-2 px-4 py-2 bg-green-600 text-white rounded-lg text-xs">
                        Confirm Approval
                    </button>

                </form>

            @else
                <div class="text-xs text-red-500">
                    ⚠ Invalid document route configuration.
                </div>
            @endif

            <button type="button"
                onclick="closeModal('approveModal')"
                class="w-full text-xs text-slate-500">
                Cancel
            </button>

        </div>
    </div>

@endif






{{-- ================= WRAPPER FUNCTIONS ================= --}}
<script>
function openSubmitModal(){ openModal('submitModal'); }
function openResubmitModal(){ openModal('resubmitModal'); }
function openApproveModal(){ openModal('approveModal'); }
function openReturnModal(){ openModal('returnModal'); }
function openRetractModal(){ openModal('retractModal'); }
function openRequestEditModal(){ openModal('requestEditModal'); }
function openAllowEditModal(){ openModal('allowEditModal'); }
function openSubmitRevisionModal(){ openModal('submitRevisionModal'); }




</script>