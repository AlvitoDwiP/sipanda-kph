@props([
    'headers' => [],
    'empty' => false,
    'loading' => false,
    'sticky' => false,
    'striped' => false,
    'compact' => false,
    'hover' => true,
    'pagination' => null,
])

@php
    $paddingClass = $compact ? 'px-3 py-2 text-[11px] sm:text-xs' : 'px-4 py-3 text-xs sm:text-sm';
    $rowHoverClass = $hover ? 'hover:bg-ui-primary-soft/30 transition-colors' : '';
    $rowStripedClass = $striped ? 'odd:bg-white even:bg-ui-primary-soft/10' : 'bg-white';
@endphp

<div class="flex flex-col w-full gap-3 text-left">
    <!-- Toolbar Section (Search, Filters, Actions) -->
    @if (isset($toolbar) || isset($search) || isset($filter) || isset($actions))
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 w-full border border-ui-border rounded-ui-lg p-3 bg-white shadow-ui-sm">
            <!-- Left Toolbar: Search & Filters -->
            <div class="flex flex-1 items-center gap-2.5 flex-wrap min-w-0">
                @if (isset($search))
                    <div class="w-full sm:w-72 shrink-0">
                        {{ $search }}
                    </div>
                @endif

                @if (isset($filter))
                    <div class="flex items-center gap-2 flex-wrap">
                        {{ $filter }}
                    </div>
                @endif
                
                @if (isset($toolbar))
                    {{ $toolbar }}
                @endif
            </div>

            <!-- Right Toolbar: Custom Actions -->
            @if (isset($actions))
                <div class="flex items-center gap-2 shrink-0 flex-wrap">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <!-- Main Table Container -->
    <div {{ $attributes->class([
        'w-full overflow-hidden border border-ui-border rounded-ui-lg bg-ui-surface shadow-ui-sm',
    ]) }}>
        <div class="w-full overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-full table-auto border-collapse text-left">
                <thead class="{{ $sticky ? 'sticky top-0 z-10' : '' }} bg-ui-primary-soft border-b border-ui-border select-none">
                    <tr>
                        @if ($headers)
                            @foreach ($headers as $header)
                                <th class="{{ $paddingClass }} font-semibold text-ui-text-secondary uppercase tracking-wider text-[10px] sm:text-xs">
                                    {{ $header }}
                                </th>
                            @endforeach
                        @else
                            {{ $thead ?? '' }}
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-ui-border/50">
                    @if ($loading)
                        @if (isset($skeleton))
                            {{ $skeleton }}
                        @else
                            @for ($r = 0; $r < 4; $r++)
                                <tr class="animate-pulse bg-white">
                                    @for ($c = 0; $c < ($headers ? count($headers) : 4); $c++)
                                        <td class="{{ $paddingClass }}">
                                            <div class="h-3.5 bg-slate-200 rounded-ui-sm w-5/6"></div>
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        @endif
                    @elseif ($empty)
                        <tr>
                            <td colspan="{{ $headers ? count($headers) : 10 }}" class="px-4 py-12">
                                @if (isset($emptyState))
                                    {{ $emptyState }}
                                @else
                                    <x-ui.empty-state 
                                        icon="database" 
                                        title="Tidak ada data" 
                                        description="Data yang Anda cari tidak ditemukan atau belum ditambahkan." 
                                    />
                                @endif
                            </td>
                        </tr>
                    @else
                        <!-- Table Body rows mapping -->
                        {{ $slot }}
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Footer -->
    @if ($pagination || isset($footer))
        <div class="mt-1 flex items-center justify-between gap-4 flex-wrap px-1">
            @if (isset($footer))
                {{ $footer }}
            @else
                <div class="flex-1 w-full">
                    {{ $pagination }}
                </div>
            @endif
        </div>
    @endif
</div>
