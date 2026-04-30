<!DOCTYPE html>


<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.partials._head')

@livewireStyles

<body class="font-sans antialiased text-slate-900"
      style="background: linear-gradient(to bottom, #f8fafc, #f1f5f9);">

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
        border: 1px solid rgb(226 232 240 / 0.7);
        border-radius: 1.25rem;
        box-shadow: 0 12px 30px -12px rgb(15 23 42 / 0.12);
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

    .content-frame {
        background: #ffffff;
        border: 0;
        border-radius: 0;
        box-shadow: none;
        min-height: calc(100vh - 80px);
    }

    @media (min-width: 640px) {
        .content-frame {
            border: 1px solid rgb(226 232 240 / 0.7);
            border-radius: 1.25rem;
            box-shadow: 0 12px 30px -12px rgb(15 23 42 / 0.12);
            min-height: calc(100vh - 120px);
        }
    }

</style>


@include('layouts.partials._flash')



<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    @include('layouts.partials._sidebar')


    <div class="flex flex-col flex-1 min-w-0">

        {{-- TOPBAR --}}
        @include('layouts.partials._topbar')



        <main class="flex-1 overflow-y-auto p-0 md:p-2">
            <div class="w-full">

                <div class="max-w-7xl mx-auto sm:px-2 lg:px-0">

                    <div class="content-frame">
                        @include('layouts.partials._content-wrapper')
                    </div>

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide) {
        lucide.createIcons();
    }
});
</script>





<script>
document.addEventListener('DOMContentLoaded', () => {

    if (!window.Echo) {
        console.log('Echo not loaded');
        return;
    }

    const userId = {{ auth()->id() ?? 'null' }};
    console.log('User ID:', userId);

    if (!userId) return;

    window.Echo.private(`App.Models.User.${userId}`)
        .subscribed(() => {
            console.log('Subscribed to private channel');
        })
        .error((err) => {
            console.error('Channel error:', err);
        })
        .notification((notification) => {

            console.log('REALTIME HIT:', notification);

            const badge = document.getElementById('notif-count');

            if (badge) {
                let current = badge.innerText === '99+' ? 99 : parseInt(badge.innerText || 0);
                badge.innerText = (current + 1) > 99 ? '99+' : current + 1;
            }

            const list = document.getElementById('notif-list');

            if (list) {
                const item = document.createElement('a');
                item.className = "block border-b border-slate-800 px-4 py-3 hover:bg-slate-800/70 transition";

                item.innerHTML = `
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 h-2.5 w-2.5 rounded-full bg-blue-400"></div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-slate-100">
                                ${notification.title ?? 'Notification'}
                            </p>
                            ${notification.message ? `
                                <p class="mt-1 text-xs text-slate-400">
                                    ${notification.message}
                                </p>
                            ` : ''}
                            <p class="mt-1 text-[10px] text-slate-500">
                                just now
                            </p>
                        </div>
                    </div>
                `;

                const empty = document.getElementById('notif-empty');
                if (empty) empty.remove();

                list.prepend(item);
            }

        });

});
</script>

@stack('scripts')
@livewireScripts
</body>
</html>