@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->class(['flex flex-col gap-3.5 border-b border-ui-border pb-4 mb-6 w-full text-left']) }}>
    <!-- Breadcrumbs Section -->
    @if (isset($breadcrumbs))
        <nav class="flex items-center gap-1.5 text-[10px] sm:text-xs text-ui-text-secondary select-none" aria-label="Breadcrumb">
            {{ $breadcrumbs }}
        </nav>
    @endif

    <!-- Content Wrapper (Title, Subtitle & Actions) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Title & Subtitle Info -->
        <div class="min-w-0 flex-1">
            <h1 class="text-base sm:text-lg font-bold text-ui-primary tracking-tight truncate leading-tight">
                {{ $title }}
            </h1>
            @if ($subtitle)
                <p class="text-xs text-ui-text-secondary mt-1 max-w-2xl leading-normal">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <!-- Action Items Slot -->
        @if (isset($actions))
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap sm:justify-end">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
