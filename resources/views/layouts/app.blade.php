<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.partials._head')

<body class="font-sans antialiased bg-slate-100 text-slate-900">

<div id="page-loader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white">
    <div class="loader"></div>
</div>

@auth
@php
$isAdmin = auth()->user()->system_role === 'sacdev_admin';
$activeSy = \App\Models\SchoolYear::activeYear();
@endphp
@endauth

<style>
    .loader {
        width: 40px;
        height: 40px;
        border: 4px solid rgb(226 232 240);
        border-top: 4px solid rgb(15 23 42); 
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }



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

    .custom-sidebar-scroll {
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.25) transparent;
    }

    .custom-sidebar-scroll::-webkit-scrollbar {
        width: 8px;
    }

    .custom-sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-sidebar-scroll::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.20);
        border-radius: 9999px;
    }

    .custom-sidebar-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.35);
    }

</style>


@include('layouts.partials._flash')


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
document.addEventListener('alpine:init', () => {

    window.initQuillEditor = function (editorId, options = {}) {

        const el = document.getElementById(editorId);
        if (!el) return null;

        el.innerHTML = '';

        const q = new Quill(el, {
            theme: 'snow',
            placeholder: options.placeholder || 'Write remarks here...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        if (options.content) {
            q.root.innerHTML = options.content;
        }

        if (options.hiddenInputId) {
            q.on('text-change', function () {
                const input = document.getElementById(options.hiddenInputId);
                if (input) {
                    input.value = q.root.innerHTML;
                }
            });
        }

        return q;
    };

});



document.addEventListener('submit', function (e) {

    const form = e.target;

    const editorId = form.dataset.quillEditor;
    const inputId  = form.dataset.quillInput;

    if (!editorId || !inputId) return;

    const editor = document.querySelector(`#${editorId} .ql-editor`);
    const input  = document.getElementById(inputId);

    if (editor && input) {
        input.value = editor.innerHTML;
    }

    const text = input.value
        .replace(/<(.|\n)*?>/g, '')
        .replace(/&nbsp;/g, ' ')
        .trim();

    if (!text) {
        e.preventDefault();
        alert('Please enter remarks.');
    }
});
</script>

<script>
window.addEventListener('load', function () {
    const loader = document.getElementById('page-loader');

    if (loader) {
        loader.style.opacity = '0';
        loader.style.transition = 'opacity 0.3s ease';

        setTimeout(() => loader.remove(), 300);
    }
});
</script>

<script>
document.addEventListener('submit', function () {
    const loader = document.getElementById('page-loader');
    if (loader) {
        loader.style.opacity = '1';
        loader.style.display = 'flex';
    }
});
</script>

</body>
</html>