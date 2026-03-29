<script>



// ================= SPECIFIC MODALS =================


function openInstructionModal() {
    openModal('instructionModal');
}

function closeInstructionModal() {
    closeModal('instructionModal');
}



// ================= BENEFICIARIES "OTHERS" TOGGLE =================
document.addEventListener('DOMContentLoaded', function () {

    const checkbox = document.getElementById('othersCheckbox');
    const input = document.getElementById('othersInput');

    if (checkbox && input) {

        function toggleInput() {
            input.disabled = !checkbox.checked;

            if (!checkbox.checked) {
                input.value = '';
            }
        }

        checkbox.addEventListener('change', toggleInput);

        toggleInput();
    }

});

</script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const status = @json($status ?? 'draft');

    if (status === 'draft') {
        openInstructionModal();
    }

});

</script>