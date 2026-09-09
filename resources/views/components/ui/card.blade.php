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
    'shortContext' => null,
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
        <div class="flex flex-col h-full justify-between gap-1 text-left select-none">
            <!-- Top Row: Title and Icon -->
            <div class="flex items-start justify-between gap-2.5">
                <span class="text-[10px] sm:text-[11px] font-bold text-ui-text-secondary uppercase tracking-wider truncate" title="{{ $title }}">{{ $title }}</span>
                @if ($icon)
                    <div class="w-8 h-8 rounded-ui-md bg-ui-primary-soft text-ui-primary flex items-center justify-center shrink-0 border border-ui-primary/10">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                    </div>
                @endif
            </div>

            <!-- Middle Row: Value and Trend Badge -->
            <div class="flex items-baseline justify-between gap-2 flex-wrap mt-0.5">
                @if ($value !== null)
                    <span class="text-xl sm:text-2xl font-extrabold text-ui-text-primary tracking-tight leading-none">{{ $value }}</span>
                @endif
                @if ($trend)
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-ui-sm text-[9px] font-extrabold uppercase tracking-wider shrink-0 {{ $trendColor }}">
                        <i data-lucide="{{ $trendIcon }}" class="w-2.5 h-2.5"></i>
                        <span>{{ $trend }}</span>
                    </span>
                @endif
            </div>

            <!-- Bottom Row: Short Context -->
            @if ($shortContext || $subtitle || $description)
                <div class="text-[10.5px] text-ui-text-secondary mt-1 font-medium truncate">
                    @if ($shortContext)
                        {{ $shortContext }}
                    @elseif ($subtitle)
                        {{ $subtitle }}
                    @else
                        {{ $description }}
                    @endif
                </div>
            @endif
        </div>
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
