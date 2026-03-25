
    <style>
        /* Page polish */
        .org-page-card {
            background: #ffffff;
            border: 1px solid rgb(226 232 240);
            border-radius: 1rem;
            box-shadow: 0 1px 2px 0 rgb(15 23 42 / 0.05);
        }

        /* DataTables wrapper spacing */
        .dataTables_wrapper {
            padding: 1rem 1.25rem 1.25rem 1.25rem;
        }

        .dataTables_wrapper .dt-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .dataTables_wrapper .dt-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid rgb(241 245 249);
            flex-wrap: wrap;
        }

       
        .dataTables_wrapper .dataTables_length {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgb(71 85 105);
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        /* Fix ugly select */
        .dataTables_wrapper .dataTables_length select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            min-width: 72px;
            height: 40px;
            padding: 0 2.25rem 0 0.875rem;
            border: 1px solid rgb(203 213 225);
            border-radius: 0.75rem;
            background-color: #fff;
            color: rgb(30 41 59);
            font-size: 0.875rem;
            line-height: 1;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 20 20' fill='none'><path d='M6 8L10 12L14 8' stroke='%2364748b' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/></svg>");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 16px;
            cursor: pointer;
            margin: 0;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            outline: none;
            border-color: rgb(59 130 246);
            box-shadow: 0 0 0 3px rgb(59 130 246 / 0.12);
        }

        /* Search area */
        .dataTables_wrapper .dataTables_filter {
            margin: 0;
        }

        .dataTables_wrapper .dataTables_filter label {
            display: block;
            width: 100%;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 320px;
            max-width: 100%;
            height: 40px;
            margin: 0;
            padding: 0 0.95rem;
            border: 1px solid rgb(203 213 225);
            border-radius: 0.75rem;
            background: #fff;
            color: rgb(30 41 59);
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_filter input::placeholder {
            color: rgb(148 163 184);
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: rgb(59 130 246);
            box-shadow: 0 0 0 3px rgb(59 130 246 / 0.12);
        }

        /* Table cleanup */
        table.dataTable {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        table.dataTable thead th {
            background: #ffffff !important;
            color: rgb(100 116 139) !important;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid rgb(226 232 240) !important;
        }

        table.dataTable tbody td {
            background: #ffffff !important;
            border-bottom: 1px solid rgb(241 245 249) !important;
            vertical-align: middle;
        }

        table.dataTable tbody tr:last-child td {
            border-bottom: none !important;
        }

        table.dataTable tbody tr:hover td {
            background: rgb(248 250 252) !important;
        }

        table.dataTable.no-footer {
            border-bottom: none !important;
        }

        /* Sort arrows spacing */
        table.dataTable thead th.sorting,
        table.dataTable thead th.sorting_asc,
        table.dataTable thead th.sorting_desc {
            padding-right: 2rem !important;
        }

        /* Bottom info */
        .dataTables_wrapper .dataTables_info {
            color: rgb(100 116 139);
            font-size: 0.875rem;
            padding-top: 0 !important;
        }

        /* Pagination */
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 0 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem !important;
            margin: 0 0.125rem;
            border: 1px solid transparent !important;
            border-radius: 0.65rem !important;
            background: transparent !important;
            color: rgb(51 65 85) !important;
            font-size: 0.875rem;
            line-height: 34px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: rgb(241 245 249) !important;
            border-color: rgb(226 232 240) !important;
            color: rgb(15 23 42) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: rgb(30 41 59) !important;
            border-color: rgb(30 41 59) !important;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            opacity: 0.45;
            background: transparent !important;
            border-color: transparent !important;
            color: rgb(148 163 184) !important;
            cursor: default;
        }

        @media (max-width: 768px) {
            .dataTables_wrapper {
                padding: 0.875rem 1rem 1rem 1rem;
            }

            .dataTables_wrapper .dt-top,
            .dataTables_wrapper .dt-bottom {
                flex-direction: column;
                align-items: stretch;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
            }

            .dataTables_wrapper .dataTables_paginate {
                width: 100%;
                text-align: left !important;
            }
        }
    </style>