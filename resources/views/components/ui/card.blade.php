@props([
    'variant' => 'default', // default, flat, bordered, dashboard, statistics, interactive, hoverable
    'title' => null,
    'subtitle' => null,
    'description' => null,
    'icon' => null,
    'value' => null,
    'trend' => null,
    'trendType' => 'up', // up, down, neutral
    'hoverable' => false,
])

@php
    $baseClasses = 'bg-ui-surface rounded-ui-lg transition-all duration-200 flex flex-col w-full text-left';
    
    $variants = [
        'default' => 'border border-ui-border shadow-ui-sm',
        'flat' => 'bg-ui-primary-soft/50 border border-transparent shadow-none',
        'bordered' => 'border border-ui-border shadow-none',
        'dashboard' => 'border border-ui-border shadow-ui-sm bg-ui-surface',
        'statistics' => 'border border-ui-border shadow-ui-sm p-4 sm:p-5 relative',
        'interactive' => 'border border-ui-border shadow-ui-sm hover:shadow-ui-md hover:border-ui-primary/30 cursor-pointer active:scale-[0.99]',
        'hoverable' => 'border border-ui-border shadow-ui-sm hover:shadow-ui-md hover:border-ui-primary-hover/30',
    ];

    $variantClass = $variants[$variant] ?? $variants['default'];
    
    // Additional hover classes if explicitly set via prop
    if ($hoverable && $variant !== 'hoverable' && $variant !== 'interactive') {
        $variantClass .= ' hover:shadow-ui-md hover:border-ui-primary/30';
    }

    $trendColors = [
        'up' => 'text-ui-success bg-ui-success-soft border border-ui-success/10',
        'down' => 'text-ui-danger bg-ui-danger-soft border border-ui-danger/10',
        'neutral' => 'text-ui-text-secondary bg-ui-primary-soft border border-ui-border',
    ];
    $trendColor = $trendColors[$trendType] ?? $trendColors['up'];

    $trendIcons = [
        'up' => 'arrow-up-right',
        'down' => 'arrow-down-right',
        'neutral' => 'minus',
    ];
    $trendIcon = $trendIcons[$trendType] ?? $trendIcons['up'];
@endphp

<div {{ $attributes->class([$baseClasses, $variantClass]) }}>
    @if ($variant === 'statistics')
        <!-- Statistics Layout -->
        <div class="flex items-start justify-between gap-3 w-full">
            <div class="flex flex-col min-w-0">
                <span class="text-xs font-semibold text-ui-text-secondary truncate">{{ $title }}</span>
                @if ($value !== null)
                    <span class="text-xl sm:text-2xl font-bold text-ui-text-primary mt-1 tracking-tight">{{ $value }}</span>
                @endif
            </div>
            @if ($icon)
                <div class="w-10 h-10 rounded-ui-md bg-ui-primary-soft text-ui-primary flex items-center justify-center shrink-0 border border-ui-primary/10">
                    <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                </div>
            @endif
        </div>

        @if ($subtitle || $trend || $description)
            <div class="flex items-center gap-2 mt-3 flex-wrap">
                @if ($trend)
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-ui-sm text-[10px] font-bold {{ $trendColor }}">
                        <i data-lucide="{{ $trendIcon }}" class="w-3 h-3"></i>
                        <span>{{ $trend }}</span>
                    </span>
                @endif
                @if ($subtitle)
                    <span class="text-[11px] text-ui-text-secondary truncate">{{ $subtitle }}</span>
                @endif
                @if ($description)
                    <p class="text-[10px] sm:text-xs text-ui-muted mt-1 leading-normal w-full">{{ $description }}</p>
                @endif
            </div>
        @endif
        
        {{ $slot }}
    @else
        <!-- Standard Card Layout -->
        @if ($title || isset($header) || $subtitle || $description)
            <div class="px-4 py-3.5 sm:px-5 border-b border-ui-border flex items-center justify-between gap-4 flex-wrap bg-ui-primary-soft/10 shrink-0">
                @if (isset($header))
                    {{ $header }}
                @else
                    <div class="flex items-start gap-2.5 min-w-0">
                        @if ($icon)
                            <div class="text-ui-primary shrink-0 mt-0.5">
                                <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <h3 class="text-xs sm:text-sm font-bold text-ui-text-primary truncate">{{ $title }}</h3>
                            @if ($subtitle)
                                <p class="text-[10px] sm:text-xs text-ui-text-secondary truncate mt-0.5">{{ $subtitle }}</p>
                            @endif
                            @if ($description)
                                <p class="text-[10px] sm:text-xs text-ui-muted mt-0.5 leading-normal">{{ $description }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                @if (isset($actions))
                    <div class="flex items-center gap-2 shrink-0">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Card Body -->
        <div class="flex-1 p-4 sm:p-5 text-xs sm:text-sm text-ui-text-primary">
            {{ $slot }}
        </div>

        <!-- Card Footer -->
        @if (isset($footer))
            <div class="px-4 py-3 sm:px-5 border-t border-ui-border bg-ui-primary-soft/5 flex items-center justify-end gap-3 shrink-0 flex-wrap">
                {{ $footer }}
            </div>
        @endif
    @endif
</div>
