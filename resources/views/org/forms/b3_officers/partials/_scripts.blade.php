<script>
(function () {

    const isLocked = @json($isLocked);

    const tbody = document.getElementById('officerRows');
    const addBtn = document.getElementById('addOfficerBtn');

    const modal = document.getElementById('officerModal');
    const modalTitle = document.getElementById('officerModalTitle');
    const saveBtn = document.getElementById('saveOfficerBtn');
    const cancelBtn = document.getElementById('cancelOfficerBtn');

    const fPosition = document.getElementById('modal_position');
    const fName = document.getElementById('modal_name');

    const fStudentId = document.getElementById('modal_student_id');
    const fCourse = document.getElementById('modal_course');


    const fFirstQpi = document.getElementById('modal_first_qpi');
    const fSecondQpi = document.getElementById('modal_second_qpi');
    
    const fInterQpi = document.getElementById('modal_inter_qpi');
    const fMobile = document.getElementById('modal_mobile');

    let editingRow = null;


    function openModal(title)
    {
        modalTitle.textContent = title;
        modal.classList.remove('hidden');
    }

    function closeModal()
    {
        modal.classList.add('hidden');
        editingRow = null;
    }


    function clearForm()
    {
        fPosition.value = '';
        fName.value = '';
        fStudentId.value = '';
        fCourse.value = '';
        fFirstQpi.value = '';
        fSecondQpi.value = '';
        fInterQpi.value = '';
        fMobile.value = '';
    }


    function reindex()
    {
        const rows = tbody.querySelectorAll('.officer-row');

        rows.forEach((row, idx) => {

            row.dataset.index = idx;

            row.querySelectorAll('input[type="hidden"]').forEach(input => {

                input.name = input.name.replace(/items\[\d+\]/, `items[${idx}]`);

            });

        });

    }


    function removeEmptyHint()
    {
        const hint = document.getElementById('emptyHint');
        if (hint) hint.remove();
    }


    function createRow(data)
    {
        removeEmptyHint();

        const idx = tbody.querySelectorAll('.officer-row').length;

        const tr = document.createElement('tr');

        tr.className = 'officer-row cursor-pointer hover:bg-slate-50';
        tr.dataset.index = idx;

        tr.innerHTML = `

            <td class="py-3 px-2 font-medium text-slate-800 officer-position">
                ${data.position ?? ''}
            </td>

            <td class="py-3 px-2 text-slate-700 officer-name">
                ${data.name ?? ''}
            </td>

            <td class="py-3 px-2 text-slate-700 officer-student-id">
                ${data.studentId ?? ''}
            </td>

            <td class="py-3 px-2 text-right space-x-2">

                <button type="button"
                    class="editOfficerBtn inline-flex rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100">
                    Edit
                </button>

                <button type="button"
                    class="removeOfficerBtn inline-flex rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                    Remove
                </button>

            </td>

            <input type="hidden"
                name="items[${idx}][position]"
                value="${data.position ?? ''}"
                class="input-position">

            <input type="hidden"
                name="items[${idx}][officer_name]"
                value="${data.name ?? ''}"
                class="input-name">

            <input type="hidden"
                name="items[${idx}][student_id_number]"
                value="${data.studentId ?? ''}"
                class="input-student-id">

            <input type="hidden"
                name="items[${idx}][course_and_year]"
                value="${data.course ?? ''}"
                class="input-course">

            <input type="hidden"
                name="items[${idx}][first_sem_qpi]"
                value="${data.firstQpi ?? ''}"
                class="input-first-qpi">

            <input type="hidden"
                name="items[${idx}][second_sem_qpi]"
                value="${data.secondQpi ?? ''}"
                class="input-second-qpi">

            <input type="hidden"
                name="items[${idx}][intersession_qpi]"
                value="${data.interQpi ?? ''}"
                class="input-intersession-qpi">

            <input type="hidden"
                name="items[${idx}][mobile_number]"
                value="${data.mobile ?? ''}"
                class="input-mobile">

        `;

        tbody.appendChild(tr);

    }


    function updateRow(row, data)
    {
        row.querySelector('.officer-position').textContent = data.position;
        row.querySelector('.officer-name').textContent = data.name;
        row.querySelector('.officer-student-id').textContent = data.studentId;

        row.querySelector('[name$="[position]"]').value = data.position;
        row.querySelector('[name$="[officer_name]"]').value = data.name;
        row.querySelector('[name$="[student_id_number]"]').value = data.studentId;
        row.querySelector('[name$="[course_and_year]"]').value = data.course;
        row.querySelector('[name$="[first_sem_qpi]"]').value = data.firstQpi;
        row.querySelector('[name$="[second_sem_qpi]"]').value = data.secondQpi;
        row.querySelector('[name$="[intersession_qpi]"]').value = data.interQpi;
        row.querySelector('[name$="[mobile_number]"]').value = data.mobile;

    }


    function readRow(row)
    {
        return {

            position: row.querySelector('[name$="[position]"]').value,
            name: row.querySelector('[name$="[officer_name]"]').value,
            studentId: row.querySelector('[name$="[student_id_number]"]').value,
            course: row.querySelector('[name$="[course_and_year]"]').value,
            firstQpi: row.querySelector('[name$="[first_sem_qpi]"]').value,
            secondQpi: row.querySelector('[name$="[second_sem_qpi]"]').value,
            interQpi: row.querySelector('[name$="[intersession_qpi]"]').value,
            mobile: row.querySelector('[name$="[mobile_number]"]').value,

        };
    }


    function readForm()
    {
        return {

            position: fPosition.value,
            name: fName.value,
            studentId: fStudentId.value,
            course: fCourse.value,
            firstQpi: fFirstQpi.value,
            secondQpi: fSecondQpi.value,
            interQpi: fInterQpi.value,
            mobile: fMobile.value,

        };
    }


    addBtn?.addEventListener('click', function () {

        clearForm();

        editingRow = null;

        openModal('Add Officer');

    });


    saveBtn.addEventListener('click', function () {

        const data = readForm();

        if (!data.name || !data.studentId)
        {
            alert('Name and Student ID required');
            return;
        }

        if (editingRow)
        {
            updateRow(editingRow, data);
        }
        else
        {
            createRow(data);
        }

        reindex();

        closeModal();

    });


    cancelBtn.addEventListener('click', closeModal);


    tbody.addEventListener('click', function (e) {

        if (e.target.classList.contains('removeOfficerBtn'))
        {
            if (!confirm('Remove this officer?')) return;

            e.target.closest('.officer-row').remove();

            reindex();
        }

        if (e.target.classList.contains('editOfficerBtn'))
        {
            const row = e.target.closest('.officer-row');

            editingRow = row;

            const data = readRow(row);

            fPosition.value = data.position;
            fName.value = data.name;
            fStudentId.value = data.studentId;
            fCourse.value = data.course;
            fFirstQpi.value = data.firstQpi;
            fSecondQpi.value = data.secondQpi;
            fInterQpi.value = data.interQpi;
            fMobile.value = data.mobile;

            openModal('Edit Officer');

        }

    });


})();
</script>