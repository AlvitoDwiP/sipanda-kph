@props([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'py-1 bg-ui-surface',
])

@php
    $alignmentClasses = [
        'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
        'right' => 'ltr:origin-top-right rtl:origin-top-left end-0',
        'center' => 'origin-top start-1/2 -translate-x-1/2',
    ][$align] ?? 'end-0';

    $widths = [
        '40' => 'w-40',
        '44' => 'w-44',
        '48' => 'w-48',
        '56' => 'w-56',
        '64' => 'w-64',
    ];
    $widthClass = $widths[$width] ?? 'w-48';
@endphp

<div 
    x-data="{ open: false }" 
    @click.outside="open = false" 
    @close.stop="open = false"
    class="relative inline-block text-left"
>
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-1.5 {{ $widthClass }} rounded-ui-md shadow-ui-md border border-ui-border overflow-hidden {{ $alignmentClasses }}"
        style="display: none;"
        @click="open = false"
    >
        <div class="{{ $contentClasses }} divide-y divide-ui-border/50 text-xs sm:text-sm text-ui-text-primary">
            {{ $slot }}
        </div>
    </div>
</div>
