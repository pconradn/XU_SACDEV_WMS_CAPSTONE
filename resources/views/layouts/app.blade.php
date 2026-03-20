<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.partials._head')

<body class="font-sans antialiased bg-slate-100 text-slate-900">

@auth
@php
$isAdmin = auth()->user()->system_role === 'sacdev_admin';
$activeSy = \App\Models\SchoolYear::activeYear();
@endphp
@endauth

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }


    .content-frame {
        background: #ffffff;
        border: 1px solid rgb(226 232 240);
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 8px 28px -12px rgb(15 23 42 / 0.08);
        min-height: calc(100vh - 120px);
    }

    .content-frame.soft {
        background: rgb(248 250 252);
    }
</style>


{{-- GLOBAL TOAST NOTIFICATIONS --}}
<div id="toast-container" class="fixed top-5 right-5 z-50 space-y-3">

    @if(session('status'))
        <div class="toast flex items-start gap-3 max-w-sm rounded-xl border border-emerald-200 bg-white shadow-lg p-4 animate-fade-in">

            {{-- ICON --}}
            <div class="text-emerald-500 mt-0.5">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>

            {{-- MESSAGE --}}
            <div class="flex-1 text-sm text-slate-700">
                {{ session('status') }}
            </div>

            {{-- CLOSE --}}
            <button onclick="closeToast(this)" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

        </div>
    @endif

    @if(session('error'))
        <div class="toast flex items-start gap-3 max-w-sm rounded-xl border border-red-200 bg-white shadow-lg p-4 animate-fade-in">

            <div class="text-red-500 mt-0.5">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
            </div>

            <div class="flex-1 text-sm text-slate-700">
                {{ session('error') }}
            </div>

            <button onclick="closeToast(this)" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

        </div>
    @endif

</div>


<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    @include('layouts.partials._sidebar')


    <div class="flex flex-col flex-1 min-w-0">

        {{-- TOPBAR --}}
        @include('layouts.partials._topbar')




        {{-- PAGE CONTENT --}}
        <main class="flex-1 overflow-y-auto p-6">

            <div class="max-w-7xl mx-auto">

                <div class="content-frame">
                    @include('layouts.partials._content-wrapper')
                </div>

            </div>

        </main>

    </div>

</div>

<script>
    function closeToast(button) {
        const toast = button.closest('.toast');
        toast.classList.add('opacity-0', 'translate-x-5');
        setTimeout(() => toast.remove(), 300);
    }

    // Auto remove after 4 seconds
    setTimeout(() => {
        document.querySelectorAll('.toast').forEach(toast => {
            toast.classList.add('opacity-0', 'translate-x-5');
            setTimeout(() => toast.remove(), 300);
        });
    }, 4000);
</script>
<script>
    let quill;

    document.addEventListener('alpine:init', () => {

        Alpine.effect(() => {
            if (document.getElementById('editor') && !quill) {

                quill = new Quill('#editor', {
                    theme: 'snow',
                    placeholder: 'Write remarks here...',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link'],
                            ['clean']
                        ]
                    }
                });

                // preload existing content
                quill.root.innerHTML = `{!! addslashes($submission->sacdev_remarks ?? '') !!}`;
            }
        });

    });

    function submitRemarks() {
        document.getElementById('remarksInput').value = quill.root.innerHTML;
    }
</script>

</body>
</html>