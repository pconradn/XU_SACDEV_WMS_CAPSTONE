    {{-- Approval preview script --}}
    <script>

        function showApprovalPreview()
        {
            document
                .getElementById('approvalPreviewModal')
                .classList.remove('hidden');

            document
                .getElementById('approvalPreviewModal')
                .classList.add('flex');
        }


        function closeApprovalPreview()
        {
            document
                .getElementById('approvalPreviewModal')
                .classList.add('hidden');

            document
                .getElementById('approvalPreviewModal')
                .classList.remove('flex');
        }


        function submitApproval()
        {
            document.getElementById('approveForm').submit();
        }

    </script>
    <script>
let returnQuill;

document.addEventListener('DOMContentLoaded', function () {

    const editorEl = document.getElementById('returnEditor');

    if (editorEl) {
        returnQuill = new Quill('#returnEditor', {
            theme: 'snow',
            placeholder: 'Write your remarks here...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            }
        });
    }

});

function syncReturnRemarks() {
    document.getElementById('returnRemarksInput').value = returnQuill.root.innerHTML;
}
</script>