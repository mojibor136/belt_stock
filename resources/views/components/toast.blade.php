<div id="toast-container" class="fixed top-4 right-4 space-y-2 z-[9999]"></div>

<script>
    const TOASTS = [];

    const toast = ({
        type = 'success',
        text = '',
        time = 4000
    }) => {
        const el = document.createElement('div');
        el.className = `flex items-center px-4 py-3 rounded shadow-lg text-white opacity-0 transition duration-300 
    ${type==='success'?'bg-green-500':'bg-red-500'}`;
        el.innerHTML =
            `<i class="${type==='success'?'ri-check-line':'ri-error-warning-line'} mr-2"></i><span>${text}</span>`;
        document.getElementById('toast-container').appendChild(el);
        setTimeout(() => el.classList.replace('opacity-0', 'opacity-100'), 50);
        setTimeout(() => {
            el.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => el.remove(), 300)
        }, time);
    };

    document.addEventListener('DOMContentLoaded', () => TOASTS.forEach(toast));
</script>

@if (session('success'))
    <script>
        TOASTS.push({
            type: 'success',
            text: @json(session('success'))
        });
    </script>
@endif
@if (session('error'))
    <script>
        TOASTS.push({
            type: 'error',
            text: @json(session('error'))
        });
    </script>
@endif
@foreach ($errors->all() as $err)
    <script>
        TOASTS.push({
            type: 'error',
            text: @json($err),
            time: 6000
        });
    </script>
@endforeach
