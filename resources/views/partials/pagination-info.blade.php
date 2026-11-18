<div class="flex flex-col gap-3 border-t border-slate-200 px-4 py-3 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
    <p>Menampilkan {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} dari {{ number_format($items->total(), 0, ',', '.') }} data</p>
    {{ $items->links() }}
</div>
