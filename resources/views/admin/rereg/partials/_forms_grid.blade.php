<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

@foreach($forms as $key => $form)

@include('admin.rereg.partials._form_card', [
    'key' => $key,
    'form' => $form
])

@endforeach

</div>