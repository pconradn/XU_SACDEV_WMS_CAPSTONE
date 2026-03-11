<script>

let currentSection = '';
let itemIndex = document.querySelectorAll('#expenseRows input[name^="items"]').length;

const table = document.getElementById('expenseRows');

const addSectionBtn = document.getElementById('addSectionBtn');
const addExpenseBtn = document.getElementById('addExpenseBtn');


/*
|--------------------------------------------------------------------------
| ADD SECTION
|--------------------------------------------------------------------------
*/

if (addSectionBtn) {

addSectionBtn.addEventListener('click', function () {

    const sectionName = prompt('Enter section name (example: Food, Materials)');

    if (!sectionName) return;

    currentSection = sectionName.trim();

    const row = document.createElement('tr');

    row.classList.add('section-row');
    row.dataset.section = currentSection;

    row.innerHTML = `
        <td colspan="7"
            class="border border-slate-300 bg-slate-100 px-2 py-1 font-semibold flex justify-between">

            <span>${currentSection}</span>

            <button type="button"
                class="text-red-600 text-[11px] remove-section-btn">
                Remove Section
            </button>

        </td>
    `;

    table.appendChild(row);

});

}


/*
|--------------------------------------------------------------------------
| ADD EXPENSE ROW
|--------------------------------------------------------------------------
*/

if (addExpenseBtn) {

addExpenseBtn.addEventListener('click', function () {

    if (!currentSection) {
        alert('Please add a section first.');
        return;
    }

    const row = document.createElement('tr');

    row.dataset.section = currentSection;

    row.innerHTML = `

<td class="border border-slate-300">
<input type="hidden"
name="items[${itemIndex}][section_label]"
value="${currentSection}">

<input type="date"
name="items[${itemIndex}][date]"
class="w-full px-2 py-1 border-0">
</td>

<td class="border border-slate-300">
<input type="text"
name="items[${itemIndex}][particulars]"
class="w-full px-2 py-1 border-0">
</td>

<td class="border border-slate-300">
<input type="number"
step="0.01"
name="items[${itemIndex}][amount]"
class="w-full px-2 py-1 border-0">
</td>

<td class="border border-slate-300">
<select name="items[${itemIndex}][source_document_type]"
class="w-full border-0 px-2 py-1">

<option value="">-</option>
<option value="OR">OR</option>
<option value="SR">SR</option>
<option value="CI">CI</option>
<option value="SI">SI</option>
<option value="AR">AR</option>
<option value="PV">PV</option>

</select>
</td>

<td class="border border-slate-300">
<input type="text"
name="items[${itemIndex}][source_document_description]"
class="w-full px-2 py-1 border-0">
</td>

<td class="border border-slate-300">
<input type="text"
name="items[${itemIndex}][or_number]"
class="w-full px-2 py-1 border-0">
</td>

<td class="border border-slate-300 text-center">
<button type="button"
class="remove-row-btn text-red-600">
✕
</button>
</td>
`;

    table.appendChild(row);

    itemIndex++;

});

}


/*
|--------------------------------------------------------------------------
| REMOVE ROW
|--------------------------------------------------------------------------
*/

document.addEventListener('click', function(e){

    if(!e.target.classList.contains('remove-row-btn')) return;

    const row = e.target.closest('tr');

    if(row) row.remove();

});


/*
|--------------------------------------------------------------------------
| REMOVE SECTION
|--------------------------------------------------------------------------
*/

document.addEventListener('click', function(e){

    if(!e.target.classList.contains('remove-section-btn')) return;

    const sectionRow = e.target.closest('.section-row');

    if(!sectionRow) return;

    const sectionName = sectionRow.dataset.section;

    if(!confirm('Remove this section? All entries in this section will be deleted.')){
        return;
    }

    document.querySelectorAll('#expenseRows tr').forEach(row => {

        if(row.dataset.section === sectionName){
            row.remove();
        }

    });

});

</script>