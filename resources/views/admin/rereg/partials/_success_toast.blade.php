@if(session('success'))

<div id="successToast"
     class="fixed top-5 right-5 z-50 flex items-center gap-3
            rounded-xl border border-emerald-200 bg-white shadow-lg
            px-5 py-4 text-emerald-900
            opacity-0 translate-y-4 transition-all duration-500">

    {{-- Icon --}}
    <div class="flex-shrink-0">

        <svg class="w-6 h-6 text-emerald-600"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             viewBox="0 0 24 24">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M5 13l4 4L19 7"/>

        </svg>

    </div>


    {{-- Text --}}
    <div>

        <div class="font-semibold">
            Success
        </div>

        <div class="text-sm text-slate-600">
            {{ session('success') }}
        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const toast = document.getElementById('successToast');

    if (!toast) return;


    // Show animation
    setTimeout(() => {

        toast.classList.remove('opacity-0', 'translate-y-4');
        toast.classList.add('opacity-100', 'translate-y-0');

    }, 100);


    // Hide after 4 seconds
    setTimeout(() => {

        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-4');

        setTimeout(() => toast.remove(), 500);

    }, 4000);

});

</script>

@endif