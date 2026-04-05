{{-- ========================= --}}
{{-- GLOBAL FLASH TOAST SYSTEM --}}
{{-- ========================= --}}
<div id="flash-container"
     class="fixed top-6 right-6 z-[9999] w-full max-w-sm space-y-3 pointer-events-none">

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="flash-card pointer-events-auto rounded-2xl border border-rose-200 bg-gradient-to-b from-rose-50 to-white shadow-sm p-4 text-xs text-rose-700">

            <div class="flex items-start gap-3">

                {{-- ICON --}}
                <div class="mt-0.5 text-rose-500">
                    ⚠
                </div>

                {{-- CONTENT --}}
                <div class="flex-1">
                    <div class="font-semibold text-sm text-rose-800 mb-1">
                        Please fix the following
                    </div>

                    <ul class="list-disc pl-4 space-y-1 text-[11px] leading-relaxed">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- CLOSE --}}
                <button onclick="closeFlash(this)"
                        class="text-slate-400 hover:text-slate-600 transition text-xs">
                    ✕
                </button>

            </div>
        </div>
    @endif


    {{-- SUCCESS --}}
    @if (session('success'))
        <div class="flash-card pointer-events-auto rounded-2xl border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white shadow-sm p-4 text-xs text-emerald-700">

            <div class="flex items-center gap-3">

                <div class="text-emerald-500">✓</div>

                <div class="flex-1 font-medium text-sm text-emerald-800">
                    {{ session('success') }}
                </div>

                <button onclick="closeFlash(this)"
                        class="text-slate-400 hover:text-slate-600 transition text-xs">
                    ✕
                </button>

            </div>
        </div>
    @endif


    {{-- ERROR --}}
    @if (session('error'))
        <div class="flash-card pointer-events-auto rounded-2xl border border-rose-200 bg-gradient-to-b from-rose-50 to-white shadow-sm p-4 text-xs text-rose-700">

            <div class="flex items-center gap-3">

                <div class="text-rose-500">✕</div>

                <div class="flex-1 font-medium text-sm text-rose-800">
                    {{ session('error') }}
                </div>

                <button onclick="closeFlash(this)"
                        class="text-slate-400 hover:text-slate-600 transition text-xs">
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
        transform: translateY(-8px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.flash-card {
    animation: flash-in 0.25s ease-out;
}
</style>


{{-- ========================= --}}
{{-- SCRIPT --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const cards = document.querySelectorAll('.flash-card');

    cards.forEach((card, index) => {

        // Stagger slightly (better stacking feel)
        const delay = 3500 + (index * 200);

        setTimeout(() => {
            fadeOut(card);
        }, delay);

    });

});

function closeFlash(btn) {
    const card = btn.closest('.flash-card');
    fadeOut(card);
}

function fadeOut(card) {
    if (!card) return;

    card.style.transition = "opacity 0.3s ease, transform 0.3s ease";
    card.style.opacity = "0";
    card.style.transform = "translateY(-6px) scale(0.98)";

    setTimeout(() => {
        card.remove();
    }, 300);
}
</script>