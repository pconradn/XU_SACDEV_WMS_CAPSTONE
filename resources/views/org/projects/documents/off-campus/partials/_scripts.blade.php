<script>

function getParticipantIndex()
{
    const tbody = document.getElementById('participantsBody');

    return tbody ? tbody.children.length : 0;
}


function addParticipant()
{
    const tbody = document.getElementById('participantsBody');

    if (!tbody) return;

    const index = getParticipantIndex();

    const row = document.createElement('tr');

    row.innerHTML = `
        <td class="border px-2 py-1">
            <input type="text"
                   name="participants[${index}][student_name]"
                   class="w-full border-0 text-[10px]">
        </td>

        <td class="border px-2 py-1">
            <input type="text"
                   name="participants[${index}][course_year]"
                   class="w-full border-0 text-[10px]">
        </td>

        <td class="border px-2 py-1">
            <input type="text"
                   name="participants[${index}][student_mobile]"
                   class="w-full border-0 text-[10px]">
        </td>

        <td class="border px-2 py-1">
            <input type="text"
                   name="participants[${index}][parent_name]"
                   class="w-full border-0 text-[10px]">
        </td>

        <td class="border px-2 py-1">
            <input type="text"
                   name="participants[${index}][parent_mobile]"
                   class="w-full border-0 text-[10px]">
        </td>

        <td class="border px-2 py-1 text-center">
            <button type="button"
                    onclick="removeParticipant(this)"
                    class="text-rose-600 text-[10px]">
                Remove
            </button>
        </td>
    `;

    tbody.appendChild(row);
}


function removeParticipant(btn)
{
    const row = btn.closest('tr');

    if (row)
    {
        row.remove();
    }
}

</script>