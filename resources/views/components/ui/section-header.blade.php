@props([
    'title',
    'description' => null,
])

<div {{ $attributes->class(['flex flex-col gap-3.5 border-b border-ui-border pb-3 mb-6 w-full text-left']) }}>
    <!-- Breadcrumbs if slotted inside section header -->
    @if (isset($breadcrumbs))
        <nav class="flex items-center gap-1.5 text-[10px] sm:text-xs text-ui-text-secondary select-none">
            {{ $breadcrumbs }}
        </nav>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Title & Description -->
        <div class="min-w-0 flex-1">
            <h2 class="text-sm sm:text-base font-bold text-ui-primary tracking-tight">
                {{ $title }}
            </h2>
            @if ($description)
                <p class="text-[11px] sm:text-xs text-ui-text-secondary mt-1 max-w-2xl leading-normal">
                    {{ $description }}
                </p>
            @endif
        </div>

        <!-- Controls: Filters and Actions -->
        @if (isset($filters) || isset($actions))
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                @if (isset($filters))
                    <div class="flex items-center gap-2">
                        {{ $filters }}
                    </div>
                @endif
                
                @if (isset($actions))
                    <div class="flex items-center gap-2">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
