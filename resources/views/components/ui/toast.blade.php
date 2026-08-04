@props([
    'variant' => 'success',
    'duration' => 4000,
])

<!-- Toast registration helper -->
<script>
    if (!window.showToast) {
        window.showToast = function(message, variant = 'success', duration = 4000) {
            const container = document.getElementById('ui-toast-container');
            if (!container) return;

            const icons = {
                success: 'check-circle',
                danger: 'x-circle',
                warning: 'alert-triangle',
                info: 'info'
            };
            const icon = icons[variant] || 'check-circle';

            const bgColors = {
                success: 'bg-white border-l-4 border-ui-success',
                danger: 'bg-white border-l-4 border-ui-danger',
                warning: 'bg-white border-l-4 border-ui-warning',
                info: 'bg-white border-l-4 border-ui-info'
            };
            const bgColor = bgColors[variant] || bgColors.success;

            const iconColors = {
                success: 'text-ui-success',
                danger: 'text-ui-danger',
                warning: 'text-ui-warning',
                info: 'text-ui-info'
            };
            const iconColor = iconColors[variant] || iconColors.success;

            // Create toast wrapper
            const toast = document.createElement('div');
            toast.className = `pointer-events-auto flex items-start gap-3 w-full p-3.5 rounded-ui-md shadow-ui-lg border border-ui-border transition-all duration-300 transform translate-y-4 opacity-0 ${bgColor} text-ui-text-primary bg-white`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'polite');
            
            toast.innerHTML = `
                <div class="${iconColor} shrink-0 mt-0.5">
                    <i data-lucide="${icon}" class="w-4.5 h-4.5"></i>
                </div>
                <div class="flex-1 text-xs sm:text-sm font-semibold leading-normal pr-4">
                    ${message}
                </div>
                <button type="button" class="shrink-0 p-0.5 rounded-ui-md hover:bg-black/5 text-ui-text-secondary transition-colors focus:outline-none" aria-label="Tutup notifikasi">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            `;

            container.appendChild(toast);
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            }, 50);

            const closeToast = () => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            };

            toast.querySelector('button').addEventListener('click', closeToast);

            if (duration > 0) {
                setTimeout(closeToast, duration);
            }
        };
    }
</script>

<!-- Check Laravel Session Flashes -->
@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.showToast("{{ session('success') }}", 'success');
        });
    </script>
@endif
@if (session('error') || session('danger'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.showToast("{{ session('error') ?: session('danger') }}", 'danger');
        });
    </script>
@endif
@if (session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.showToast("{{ session('warning') }}", 'warning');
        });
    </script>
@endif
@if (session('info') || session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.showToast("{{ session('info') ?: session('status') }}", 'info');
        });
    </script>
@endif
