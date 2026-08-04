@props([
    'name',
    'show' => false,
    'title' => null,
    'maxWidth' => 'md',
])

@php
    $widths = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
    ];
    $widthClass = $widths[$maxWidth] ?? $widths['md'];
@endphp

<dialog
    id="dialog-{{ $name }}"
    x-data="{
        show: @js($show),
        init() {
            this.$watch('show', value => {
                if (value) {
                    if (!this.$el.open) {
                        this.$el.showModal();
                        document.body.classList.add('overflow-hidden');
                    }
                } else {
                    if (this.$el.open) {
                        this.$el.close();
                        document.body.classList.remove('overflow-hidden');
                    }
                }
            });
            
            // Sync state if closed via ESC key natively
            this.$el.addEventListener('close', () => {
                this.show = false;
                document.body.classList.remove('overflow-hidden');
            });
            
            // Check initial state
            if (this.show && !this.$el.open) {
                this.$el.showModal();
                document.body.classList.add('overflow-hidden');
            }
        }
    }"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:click="
        const rect = $el.getBoundingClientRect();
        const isInDialog = (rect.top <= $event.clientY && $event.clientY <= rect.top + rect.height &&
                            rect.left <= $event.clientX && $event.clientX <= rect.left + rect.width);
        if (!isInDialog) {
            show = false;
        }
    "
    {{ $attributes->class([
        'rounded-ui-xl border border-ui-border shadow-ui-lg bg-ui-surface p-0 outline-none w-full max-h-[85vh]',
        $widthClass,
    ]) }}
    style="display: none;"
>
    <!-- Modal Container -->
    <div class="flex flex-col h-full max-h-[85vh] text-left">
        <!-- Modal Header -->
        @if ($title || isset($header))
            <div class="px-4 py-3.5 sm:px-5 border-b border-ui-border flex items-center justify-between gap-4 bg-ui-primary-soft/30 shrink-0 select-none">
                @if (isset($header))
                    {{ $header }}
                @else
                    <h3 class="text-xs sm:text-sm font-bold text-ui-text-primary truncate">{{ $title }}</h3>
                @endif

                <button 
                    type="button" 
                    @click="show = false"
                    class="p-1 rounded-ui-md hover:bg-black/5 text-ui-text-secondary transition-colors focus:outline-none"
                    aria-label="Tutup modal"
                >
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @else
            <!-- Floating close button wrapper if header is missing -->
            <div class="absolute top-3.5 right-3.5 z-10">
                <button 
                    type="button" 
                    @click="show = false"
                    class="p-1 rounded-ui-md bg-white/85 hover:bg-white border border-ui-border text-ui-text-secondary transition-colors focus:outline-none shadow-sm"
                    aria-label="Tutup modal"
                >
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        <!-- Modal Body -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-5 text-xs sm:text-sm text-ui-text-primary leading-relaxed">
            {{ $slot }}
        </div>

        <!-- Modal Footer -->
        @if (isset($footer))
            <div class="px-4 py-3 sm:px-5 border-t border-ui-border bg-ui-primary-soft/10 flex items-center justify-end gap-3 shrink-0 flex-wrap">
                {{ $footer }}
            </div>
        @endif
    </div>
</dialog>

<style>
    /* Styling backdrop of dialog modal using CSS variables and tailwind values */
    #dialog-{{ $name }}::backdrop {
        background-color: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(2px);
    }
    
    #dialog-{{ $name }}[open] {
        display: block;
        animation: ui-modal-zoom 300ms cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    @keyframes ui-modal-zoom {
        from {
            transform: scale(0.95);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>
