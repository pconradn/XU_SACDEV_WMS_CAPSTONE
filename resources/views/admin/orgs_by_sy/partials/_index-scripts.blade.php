    <script>
        $(function () {
            $('#orgTable').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50],
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: false,
                language: {
                    search: '',
                    searchPlaceholder: 'Search organizations...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ organizations',
                    paginate: {
                        previous: 'Previous',
                        next: 'Next'
                    }
                },
                dom:
                    "<'dt-top'<'dataTables_length'l><'dataTables_filter'f>>" +
                    "t" +
                    "<'dt-bottom'<'dataTables_info'i><'dataTables_paginate'p>>"
            });
        });
    </script>