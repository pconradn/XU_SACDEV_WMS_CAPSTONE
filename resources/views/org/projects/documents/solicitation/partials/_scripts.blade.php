<script>

function openResubmitModal() {
    const modal = document.getElementById('resubmitModal');
    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeResubmitModal() {
    const modal = document.getElementById('resubmitModal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


function openReturnModal() {
    const modal = document.getElementById('returnModal');
    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReturnModal() {
    const modal = document.getElementById('returnModal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
function openInstructionsModal() {
    const modal = document.getElementById('instructionsModal');
    modal?.classList.remove('hidden');
    modal?.classList.add('flex');
}

function closeInstructionsModal() {
    const modal = document.getElementById('instructionsModal');
    modal?.classList.add('hidden');
    modal?.classList.remove('flex');
}


function openAgreementModal() {
    const modal = document.getElementById('agreementModal');
    modal?.classList.remove('hidden');
    modal?.classList.add('flex');
}

function closeAgreementModal() {
    const modal = document.getElementById('agreementModal');
    modal?.classList.add('hidden');
    modal?.classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', function() {

    const isProjectHead = @json($isProjectHead);
    const status = @json($status);

    if (isProjectHead && status === 'draft') {
        openInstructionsModal();
    }

});




</script>