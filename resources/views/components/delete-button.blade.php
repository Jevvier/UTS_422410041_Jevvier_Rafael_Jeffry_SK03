@props(['action', 'label' => 'Hapus', 'pesan' => 'Data ini akan dihapus permanen dan tidak dapat dikembalikan. Lanjutkan?'])
<div x-data="{ open: false }" class="inline">
    <button type="button" @click="open = true"
            class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/50">
        {{ $label }}
    </button>

    <template x-teleport="body">
        <div x-show="open" x-cloak @keydown.escape.window="open = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="open" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="card relative w-full max-w-md p-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-950 dark:text-rose-300">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Konfirmasi hapus</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $pesan }}</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="open = false" class="btn-ghost">Batal</button>
                    <form method="POST" action="{{ $action }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Ya, hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
