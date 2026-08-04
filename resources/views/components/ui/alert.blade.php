@props([
    'variant' => 'info', // success, warning, danger, info
    'dismissible' => true,
    'icon' => null,
    'title' => null,
    'description' => null,
])

@php
    $baseClasses = 'relative w-full rounded-ui-lg border p-4 shadow-ui-sm transition-all duration-300 flex items-start gap-3 text-xs sm:text-sm';

    $variants = [
        'success' => [
            'bg' => 'bg-ui-success-soft text-ui-success border-ui-success/20',
            'icon' => 'check-circle',
            'iconColor' => 'text-ui-success',
        ],
        'warning' => [
            'bg' => 'bg-ui-warning-soft text-ui-warning border-ui-warning/20',
            'icon' => 'alert-triangle',
            'iconColor' => 'text-ui-warning',
        ],
        'danger' => [
            'bg' => 'bg-ui-danger-soft text-ui-danger border-ui-danger/20',
            'icon' => 'x-circle',
            'iconColor' => 'text-ui-danger',
        ],
        'info' => [
            'bg' => 'bg-ui-info-soft text-ui-info border-ui-info/20',
            'icon' => 'info',
            'iconColor' => 'text-ui-info',
        ],
    ];

    $currentVariant = $variants[$variant] ?? $variants['info'];
    $iconName = $icon ?? $currentVariant['icon'];
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    {{ $attributes->class([$baseClasses, $currentVariant['bg']]) }}
>
    <!-- Icon -->
    <div class="{{ $currentVariant['iconColor'] }} shrink-0 mt-0.5">
        <i data-lucide="{{ $iconName }}" class="w-4 h-4 sm:w-5 sm:h-5"></i>
    </div>

    <!-- Content -->
    <div class="flex-1 flex flex-col gap-1 pr-6 text-left">
        @if ($title)
            <span class="font-bold text-ui-text-primary text-xs sm:text-sm">{{ $title }}</span>
        @endif
        @if ($description || $slot->isNotEmpty())
            <div class="text-ui-text-secondary leading-relaxed text-xs">
                @if ($description)
                    {{ $description }}
                @else
                    {{ $slot }}
                @endif
            </div>
        @endif
    </div>

    <!-- Close button -->
    @if ($dismissible)
        <button 
            type="button" 
            @click="show = false"
            class="absolute top-3.5 right-3.5 p-1 rounded-ui-md hover:bg-black/5 text-ui-text-secondary transition-colors focus:outline-none"
            aria-label="Dismiss alert"
        >
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    @endif
</div>
