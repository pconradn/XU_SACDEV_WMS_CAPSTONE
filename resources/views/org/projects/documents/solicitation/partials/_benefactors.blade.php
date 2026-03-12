<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2">

<div class="text-[12px] font-semibold text-slate-900 tracking-wide">
Target Benefactors
</div>

</div>


<div class="px-4 py-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-[12px]">

<label class="flex items-center gap-2">

<input type="checkbox"
       name="target_student_orgs"
       value="1"
       {{ old('target_student_orgs', $data->target_student_orgs ?? false) ? 'checked' : '' }}>

Student Organizations within XU

</label>


<label class="flex items-center gap-2">

<input type="checkbox"
       name="target_xu_officers"
       value="1"
       {{ old('target_xu_officers', $data->target_xu_officers ?? false) ? 'checked' : '' }}>

University Officers

</label>


<label class="flex items-center gap-2">

<input type="checkbox"
       name="target_private_individuals"
       value="1"
       {{ old('target_private_individuals', $data->target_private_individuals ?? false) ? 'checked' : '' }}>

Private Individuals / Relatives

</label>


<label class="flex items-center gap-2">

<input type="checkbox"
       name="target_alumni"
       value="1"
       {{ old('target_alumni', $data->target_alumni ?? false) ? 'checked' : '' }}>

Alumni

</label>


<label class="flex items-center gap-2">

<input type="checkbox"
       name="target_private_companies"
       value="1"
       {{ old('target_private_companies', $data->target_private_companies ?? false) ? 'checked' : '' }}>

Private Companies

</label>


<label class="flex items-center gap-2">

<input type="checkbox"
       name="target_others"
       value="1"
       {{ old('target_others', $data->target_others ?? false) ? 'checked' : '' }}>

Others

</label>


<input
type="text"
name="target_others_specify"
value="{{ old('target_others_specify', $data->target_others_specify ?? '') }}"
placeholder="Please specify"
class="border border-slate-300 px-2 py-1 text-[12px]">

</div>

</div>