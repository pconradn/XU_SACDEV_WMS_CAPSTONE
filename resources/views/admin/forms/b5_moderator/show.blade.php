<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">

        <div x-data="{ openReturn: false, openAllowEdit: false, openRevertApproval: false }">

            @include('admin.forms.b5_moderator.partials._header', ['submission' => $submission])


            @include('admin.forms.b5_moderator.partials._allow_edit_modal', ['submission' => $submission])




            @include('admin.forms.b5_moderator.partials._information', ['submission' => $submission])
            @include('admin.forms.b5_moderator.partials._leadership', ['submission' => $submission])
            @include('admin.forms.b5_moderator.partials._background', ['submission' => $submission])


            @include('admin.forms.b5_moderator.partials._return_modal', ['submission' => $submission])

            @include('admin.forms.b5_moderator.partials._actions', ['submission' => $submission])

        </div> 

    </div>

    @include('admin.forms.b5_moderator.partials._scripts')

</x-app-layout>