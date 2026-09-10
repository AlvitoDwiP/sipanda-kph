@props([
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'disabled' => false,
    'href' => null,
    'leadingIcon' => null,
    'trailingIcon' => null,
    'fullWidth' => false,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary/30 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 select-none';

    $variants = [
        'primary' => 'bg-ui-primary text-white hover:bg-ui-primary-hover active:bg-ui-primary-hover border border-transparent shadow-ui-sm',
        'secondary' => 'bg-ui-primary-soft text-ui-primary hover:bg-ui-border active:bg-ui-border border border-transparent',
        'outline' => 'border border-ui-border bg-transparent text-ui-text-primary hover:bg-ui-primary-soft active:bg-ui-primary-soft',
        'ghost' => 'bg-transparent text-ui-text-primary hover:bg-ui-primary-soft active:bg-ui-primary-soft border border-transparent',
        'danger' => 'bg-ui-danger text-white hover:bg-red-700 active:bg-red-800 border border-transparent shadow-ui-sm',
        'warning' => 'bg-ui-warning text-white hover:bg-amber-700 active:bg-amber-800 border border-transparent shadow-ui-sm',
        'success' => 'bg-ui-success text-white hover:bg-emerald-700 active:bg-emerald-800 border border-transparent shadow-ui-sm',
        'info' => 'bg-ui-info text-white hover:bg-blue-700 active:bg-blue-800 border border-transparent shadow-ui-sm',
    ];

    $sizes = [
        'xs' => 'h-7 px-2.5 py-1 text-[10px] sm:text-xs rounded-ui-sm gap-1',
        'sm' => 'h-9 px-3 py-1.5 text-xs rounded-ui-md gap-1.5',
        'md' => 'h-10 px-4 py-2 text-xs sm:text-sm rounded-ui-md gap-2',
        'lg' => 'h-11 px-5 py-2.5 text-sm sm:text-base rounded-ui-lg gap-2.5',
        'xl' => 'h-12 px-6 py-3 text-base rounded-ui-xl gap-3',
    ];

    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $widthClass = $fullWidth ? 'w-full flex' : 'inline-flex';
    
    $classes = "{$baseClasses} {$variantClass} {$sizeClass} {$widthClass}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class([$classes]) }} @if($disabled) tabindex="-1" aria-disabled="true" @endif>
        @if ($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif ($leadingIcon)
            <i data-lucide="{{ $leadingIcon }}" class="w-4 h-4 shrink-0" aria-hidden="true"></i>
        @endif
        
        <span>{{ $slot }}</span>
        
        @if (!$loading && $trailingIcon)
            <i data-lucide="{{ $trailingIcon }}" class="w-4 h-4 shrink-0" aria-hidden="true"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$classes]) }} {{ $disabled || $loading ? 'disabled' : '' }}>
        @if ($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif ($leadingIcon)
            <i data-lucide="{{ $leadingIcon }}" class="w-4 h-4 shrink-0" aria-hidden="true"></i>
        @endif
        
        <span>{{ $slot }}</span>
        
        @if (!$loading && $trailingIcon)
            <i data-lucide="{{ $trailingIcon }}" class="w-4 h-4 shrink-0" aria-hidden="true"></i>
        @endif
    </button>
@endif
