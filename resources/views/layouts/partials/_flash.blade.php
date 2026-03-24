{{-- ========================= --}}
{{-- GLOBAL FLASH TOAST SYSTEM --}}
{{-- ========================= --}}
<div id="flash-container"
     class="fixed top-6 right-6 z-[9999] space-y-3 w-full max-w-sm pointer-events-none">

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="flash-card pointer-events-auto border border-rose-200 bg-white shadow-xl rounded-xl p-4 text-sm text-rose-700 animate-flash-in">

            <div class="flex items-start gap-2">

                <span class="text-rose-500 text-lg">⚠</span>

                <div class="flex-1">
                    <div class="font-semibold mb-1">
                        Please fix the following:
                    </div>

                    <ul class="list-disc pl-5 space-y-1 text-[12px]">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>

                <button onclick="this.closest('.flash-card').remove()"
                        class="text-slate-400 hover:text-slate-600 text-xs">
                    ✕
                </button>

            </div>

        </div>
    @endif


    {{-- SUCCESS --}}
    @if (session('success'))
        <div class="flash-card pointer-events-auto border border-emerald-200 bg-white shadow-xl rounded-xl p-4 text-sm text-emerald-700 animate-flash-in">

            <div class="flex items-center gap-2">

                <span class="text-emerald-500 text-lg">✓</span>

                <div class="flex-1 font-medium">
                    {{ session('success') }}
                </div>

                <button onclick="this.closest('.flash-card').remove()"
                        class="text-slate-400 hover:text-slate-600 text-xs">
                    ✕
                </button>

            </div>

        </div>
    @endif


    {{-- ERROR --}}
    @if (session('error'))
        <div class="flash-card pointer-events-auto border border-rose-200 bg-white shadow-xl rounded-xl p-4 text-sm text-rose-700 animate-flash-in">

            <div class="flex items-center gap-2">

                <span class="text-rose-500 text-lg">✕</span>

                <div class="flex-1 font-medium">
                    {{ session('error') }}
                </div>

                <button onclick="this.closest('.flash-card').remove()"
                        class="text-slate-400 hover:text-slate-600 text-xs">
                    ✕
                </button>

            </div>

        </div>
    @endif

</div>



{{-- ========================= --}}
{{-- ANIMATION STYLES --}}
{{-- ========================= --}}
<style>
@keyframes flash-in {
    from {
        opacity: 0;
        transform: translateY(-12px) scale(0.96);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.animate-flash-in {
    animation: flash-in 0.25s ease-out;
}
</style>



{{-- ========================= --}}
{{-- AUTO DISMISS SCRIPT --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const flashCards = document.querySelectorAll('.flash-card');

    flashCards.forEach(card => {

        // Fade out
        setTimeout(() => {
            card.style.transition = "opacity 0.35s ease, transform 0.35s ease";
            card.style.opacity = "0";
            card.style.transform = "translateY(-10px)";
        }, 3500);

        // Remove
        setTimeout(() => {
            card.remove();
        }, 4000);

    });

});
</script>