@props([
    'variant' => 'primary', // primary, success, warning, danger, info, neutral
    'styleType' => 'soft', // solid, soft, outline, dot
    'size' => 'md', // sm, md
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold tracking-wide rounded-full text-[10px] sm:text-[10.5px] select-none shrink-0';

    // Color systems mapping
    $colors = [
        'primary' => [
            'solid' => 'bg-ui-primary text-white border border-transparent shadow-sm',
            'soft' => 'bg-ui-primary-soft text-ui-primary border border-ui-primary/10',
            'outline' => 'border border-ui-primary text-ui-primary bg-transparent',
            'dot' => 'text-ui-text-primary bg-transparent font-semibold pl-1.5',
            'dotBg' => 'bg-ui-primary',
        ],
        'success' => [
            'solid' => 'bg-ui-success text-white border border-transparent shadow-sm',
            'soft' => 'bg-ui-success-soft text-ui-success border border-ui-success/15',
            'outline' => 'border border-ui-success text-ui-success bg-transparent',
            'dot' => 'text-ui-text-primary bg-transparent font-semibold pl-1.5',
            'dotBg' => 'bg-ui-success',
        ],
        'warning' => [
            'solid' => 'bg-ui-warning text-white border border-transparent shadow-sm',
            'soft' => 'bg-ui-warning-soft text-ui-warning border border-ui-warning/15',
            'outline' => 'border border-ui-warning text-ui-warning bg-transparent',
            'dot' => 'text-ui-text-primary bg-transparent font-semibold pl-1.5',
            'dotBg' => 'bg-ui-warning',
        ],
        'danger' => [
            'solid' => 'bg-ui-danger text-white border border-transparent shadow-sm',
            'soft' => 'bg-ui-danger-soft text-ui-danger border border-ui-danger/15',
            'outline' => 'border border-ui-danger text-ui-danger bg-transparent',
            'dot' => 'text-ui-text-primary bg-transparent font-semibold pl-1.5',
            'dotBg' => 'bg-ui-danger',
        ],
        'info' => [
            'solid' => 'bg-ui-info text-white border border-transparent shadow-sm',
            'soft' => 'bg-ui-info-soft text-ui-info border border-ui-info/15',
            'outline' => 'border border-ui-info text-ui-info bg-transparent',
            'dot' => 'text-ui-text-primary bg-transparent font-semibold pl-1.5',
            'dotBg' => 'bg-ui-info',
        ],
        'neutral' => [
            'solid' => 'bg-ui-muted text-white border border-transparent shadow-sm',
            'soft' => 'bg-ui-primary-soft text-ui-text-secondary border border-ui-border',
            'outline' => 'border border-ui-border text-ui-text-secondary bg-transparent',
            'dot' => 'text-ui-text-primary bg-transparent font-semibold pl-1.5',
            'dotBg' => 'bg-ui-muted',
        ],
    ];

    $currentColor = $colors[$variant] ?? $colors['primary'];
    $styleClass = $currentColor[$styleType] ?? $currentColor['soft'];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-[9px] gap-1',
        'md' => 'px-2.5 py-0.5 text-[10px] sm:text-[10.5px] gap-1.5',
    ];

    // Dot mode size adjustments
    if ($styleType === 'dot') {
        $sizes = [
            'sm' => 'py-0.5 pr-1 gap-1 text-[10px]',
            'md' => 'py-0.5 pr-1.5 gap-1.5 text-[11px]',
        ];
    }

    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $classes = "{$baseClasses} {$styleClass} {$sizeClass}";
@endphp

<span {{ $attributes->class([$classes]) }}>
    @if ($styleType === 'dot')
        <span class="w-1.5 h-1.5 rounded-full {{ $currentColor['dotBg'] }} shrink-0"></span>
    @endif
    {{ $slot }}
</span>
