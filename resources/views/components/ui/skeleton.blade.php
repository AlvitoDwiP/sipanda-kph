@props([
    'type' => 'card', // card, table, dashboard, form, list
    'rows' => 4,
    'cols' => 4,
])

<div {{ $attributes->class(['animate-pulse w-full']) }} aria-hidden="true">
    @if ($type === 'table')
        <!-- Table Skeleton -->
        <div class="border border-ui-border rounded-ui-lg overflow-hidden bg-ui-surface">
            <div class="h-10 bg-ui-primary-soft border-b border-ui-border"></div>
            <div class="p-3 divide-y divide-ui-border/50">
                @for ($i = 0; $i < $rows; $i++)
                    <div class="py-3.5 flex items-center justify-between gap-4">
                        @for ($j = 0; $j < $cols; $j++)
                            <div class="h-3 bg-slate-200 rounded-ui-sm flex-1 first:flex-none first:w-1/4"></div>
                        @endfor
                    </div>
                @endfor
            </div>
        </div>

    @elseif ($type === 'list')
        <!-- List Skeleton -->
        <div class="flex flex-col gap-3">
            @for ($i = 0; $i < $rows; $i++)
                <div class="flex items-center gap-3 p-3 border border-ui-border bg-ui-surface rounded-ui-md">
                    <div class="w-8.5 h-8.5 rounded-full bg-slate-200 shrink-0"></div>
                    <div class="flex-1 flex flex-col gap-1.5 min-w-0">
                        <div class="h-3.5 bg-slate-200 rounded-ui-sm w-1/3"></div>
                        <div class="h-2.5 bg-slate-200 rounded-ui-sm w-2/3"></div>
                    </div>
                </div>
            @endfor
        </div>

    @elseif ($type === 'form')
        <!-- Form Skeleton -->
        <div class="flex flex-col gap-5 p-4 sm:p-5 border border-ui-border bg-ui-surface rounded-ui-lg">
            @for ($i = 0; $i < $rows; $i++)
                <div class="flex flex-col gap-2">
                    <div class="h-3 bg-slate-200 rounded-ui-sm w-1/5"></div>
                    <div class="h-10 bg-slate-100 rounded-ui-md w-full border border-ui-border/30"></div>
                </div>
            @endfor
            <div class="flex items-center justify-end gap-3 mt-2">
                <div class="h-10 bg-slate-200 rounded-ui-md w-24"></div>
                <div class="h-10 bg-slate-200 rounded-ui-md w-32"></div>
            </div>
        </div>

    @elseif ($type === 'dashboard')
        <!-- Dashboard Skeleton -->
        <div class="flex flex-col gap-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @for ($i = 0; $i < 4; $i++)
                    <div class="p-4 border border-ui-border bg-ui-surface rounded-ui-lg flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div class="h-3 bg-slate-200 rounded-ui-sm w-1/2"></div>
                            <div class="w-8 h-8 rounded-ui-md bg-slate-200"></div>
                        </div>
                        <div class="h-5 bg-slate-200 rounded-ui-sm w-1/3 mt-1"></div>
                        <div class="h-2.5 bg-slate-200 rounded-ui-sm w-2/3"></div>
                    </div>
                @endfor
            </div>

            <!-- Main Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 flex flex-col gap-4">
                    <div class="h-7 bg-slate-200 rounded-ui-sm w-1/4"></div>
                    <x-ui.skeleton type="table" :rows="4" :cols="3" />
                </div>
                <div class="flex flex-col gap-4">
                    <div class="h-7 bg-slate-200 rounded-ui-sm w-1/3"></div>
                    <x-ui.skeleton type="list" :rows="4" />
                </div>
            </div>
        </div>

    @else
        <!-- Card Skeleton -->
        <div class="p-5 border border-ui-border bg-ui-surface rounded-ui-lg flex flex-col gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-200 shrink-0"></div>
                <div class="flex-1 flex flex-col gap-1.5 min-w-0">
                    <div class="h-3.5 bg-slate-200 rounded-ui-sm w-1/2"></div>
                    <div class="h-2 bg-slate-200 rounded-ui-sm w-1/3"></div>
                </div>
            </div>
            <div class="flex-1 flex flex-col gap-2 mt-2">
                <div class="h-3 bg-slate-200 rounded-ui-sm"></div>
                <div class="h-3 bg-slate-200 rounded-ui-sm"></div>
                <div class="h-3 bg-slate-200 rounded-ui-sm w-5/6"></div>
            </div>
            <div class="h-9 bg-slate-200 rounded-ui-md mt-4 w-1/4 self-end"></div>
        </div>
    @endif
</div>
