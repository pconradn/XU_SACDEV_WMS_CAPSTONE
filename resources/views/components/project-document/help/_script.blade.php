<script>
    function openHelpModal() {
        const modal = document.getElementById('helpModal');
        if (!modal) return;

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeHelpModal() {
        const modal = document.getElementById('helpModal');
        if (!modal) return;

        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeHelpModal();
        }
    });
</script>