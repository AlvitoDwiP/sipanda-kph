<footer class="px-6 py-3 bg-ui-surface border-t border-ui-border text-[11px] text-ui-text-secondary select-none shrink-0 font-medium">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-2">
        <p>&copy; {{ date('Y') }} SIPANDA-KPH PERHUTANI. Hak Cipta Dilindungi Undang-Undang.</p>
        <div class="flex items-center gap-3 text-[10px] text-ui-muted">
            <span>Versi 1.2.0</span>
            <span class="w-1 h-1 rounded-full bg-ui-border"></span>
            <span>Laravel v{{ app()->version() }}</span>
        </div>
    </div>
</footer>
