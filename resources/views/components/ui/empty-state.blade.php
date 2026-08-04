@props([
    'icon' => 'database',
    'title' => 'Tidak ada data',
    'description' => null,
])

<div {{ $attributes->class(['flex flex-col items-center justify-center text-center p-6 sm:p-10 bg-ui-surface rounded-ui-lg border border-ui-border max-w-lg mx-auto shadow-ui-sm']) }}>
    <!-- Illustration or Icon -->
    @if (isset($illustration))
        <div class="mb-4 shrink-0 flex items-center justify-center">
            {{ $illustration }}
        </div>
    @else
        <div class="w-12 h-12 rounded-full bg-ui-primary-soft text-ui-primary flex items-center justify-center mb-4 shrink-0 border border-ui-primary/10">
            <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
        </div>
    @endif

    <!-- Text Header -->
    <h3 class="text-sm sm:text-base font-bold text-ui-text-primary mb-1.5 leading-tight">
        {{ $title }}
    </h3>

    <!-- Description -->
    @if ($description || $slot->isNotEmpty())
        <p class="text-xs sm:text-sm text-ui-text-secondary max-w-sm mb-5 leading-relaxed">
            @if ($description)
                {{ $description }}
            @else
                {{ $slot }}
            @endif
        </p>
    @endif

    <!-- Actions -->
    @if (isset($primaryAction) || isset($secondaryAction))
        <div class="flex items-center justify-center gap-2.5 shrink-0 flex-wrap">
            @if (isset($secondaryAction))
                {{ $secondaryAction }}
            @endif
            
            @if (isset($primaryAction))
                {{ $primaryAction }}
            @endif
        </div>
    @endif
</div>
