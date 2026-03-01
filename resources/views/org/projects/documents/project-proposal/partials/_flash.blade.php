
<div id="flash-container"
     class="fixed top-6 right-6 z-50 space-y-3 w-full max-w-sm">

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="flash-card border border-rose-200 bg-white shadow-lg rounded-xl p-4 text-sm text-rose-700 animate-fade-in">
            <div class="font-semibold mb-2 flex items-center gap-2">
                <span class="text-rose-600">⚠</span>
                Please fix the following:
            </div>

            <ul class="list-disc pl-5 space-y-1 text-[12px]">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success --}}
    @if (session('success'))
        <div class="flash-card border border-emerald-200 bg-white shadow-lg rounded-xl p-4 text-sm text-emerald-700 animate-fade-in">
            <div class="flex items-center gap-2 font-medium">
                <span class="text-emerald-600">✓</span>
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Error --}}
    @if (session('error'))
        <div class="flash-card border border-rose-200 bg-white shadow-lg rounded-xl p-4 text-sm text-rose-700 animate-fade-in">
            <div class="flex items-center gap-2 font-medium">
                <span class="text-rose-600">✕</span>
                {{ session('error') }}
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const flashCards = document.querySelectorAll('.flash-card');

    flashCards.forEach(card => {
        setTimeout(() => {
            card.style.transition = "opacity 0.4s ease, transform 0.4s ease";
            card.style.opacity = "0";
            card.style.transform = "translateY(-10px)";
        }, 3500);

        setTimeout(() => {
            card.remove();
        }, 4000);
    });

});
</script>