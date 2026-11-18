@props(['judul' => 'Belum ada data', 'deskripsi' => null])
<div class="flex flex-col items-center justify-center gap-3 px-6 py-14 text-center">
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor"><path d="M3 12h2v4H3zm4-4h2v12H7zm4-3h2v18h-2zm4 4h2v10h-2zm4-2h2v12h-2z"/></svg>
    </div>
    <p class="font-bold">{{ $judul }}</p>
    @if ($deskripsi)<p class="max-w-sm text-sm text-slate-500">{{ $deskripsi }}</p>@endif
    {{ $slot }}
</div>
