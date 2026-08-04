@props([
    'items' => [],
])

<div {{ $attributes->class(['relative border-l-2 border-ui-border pl-6 ml-3.5 space-y-6 py-1 text-left']) }}>
    @forelse ($items as $item)
        <div class="relative">
            <!-- Timeline Node Point with Icon -->
            <span class="absolute -left-[35px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-ui-surface border-2 border-ui-border text-ui-primary shadow-sm shrink-0">
                @if (isset($item['icon']))
                    <i data-lucide="{{ $item['icon'] }}" class="w-3.5 h-3.5"></i>
                @else
                    <span class="w-2 h-2 rounded-full bg-ui-primary"></span>
                @endif
            </span>
            
            <!-- Node Content Area -->
            <div class="min-w-0 flex-1">
                @if (isset($item['timestamp']))
                    <span class="text-[10px] sm:text-xs text-ui-muted font-bold block leading-none mb-1">
                        {{ $item['timestamp'] }}
                    </span>
                @endif
                
                <div class="text-xs sm:text-sm text-ui-text-primary leading-normal font-semibold">
                    {!! $item['description'] !!}
                </div>
                
                @if (isset($item['details']))
                    <p class="text-[11px] sm:text-xs text-ui-text-secondary mt-1 max-w-xl leading-normal font-medium">
                        {{ $item['details'] }}
                    </p>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-6 text-ui-muted">
            Tidak ada data aktivitas terbaru.
        </div>
    @endforelse
</div>
