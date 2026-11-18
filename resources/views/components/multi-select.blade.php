{{--
  Komponen pilihan jamak (multi-select) dengan pencarian, pilih semua, dan
  penanggung jawab opsional. Hasil dikirim sebagai array {{ $name }}[].
  $items: Collection/array berisi ['id' => ..., 'label' => ..., 'sub' => ...]
--}}
@props(['name', 'items', 'selected' => [], 'pjName' => null, 'pjSelected' => null, 'judul' => 'Pilih item', 'placeholder' => 'Cari…'])
@php
    $daftar = collect($items)->map(fn ($i) => [
        'id' => (int) $i['id'],
        'label' => (string) $i['label'],
        'sub' => (string) ($i['sub'] ?? ''),
    ])->values()->all();
@endphp

<div x-data="multiSelect({
        items: @js($daftar),
        selected: @js(array_map('intval', (array) $selected)),
        pj: @js($pjSelected !== null ? (int) $pjSelected : null),
        usePj: @js((bool) $pjName),
     })"
     class="space-y-3 rounded-2xl border border-slate-200 bg-slate-50/60 p-4 dark:border-slate-800 dark:bg-slate-950/40">

    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <input type="search" x-model="cari" placeholder="{{ $placeholder }}" class="field sm:max-w-xs" aria-label="Cari {{ $judul }}">
        <div class="flex items-center gap-2 sm:ml-auto">
            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                <span x-text="selected.length"></span> dipilih
            </span>
            <button type="button" @click="semua()" class="text-xs font-semibold text-emerald-700 hover:underline dark:text-emerald-300">Pilih semua</button>
            <button type="button" @click="kosongkan()" class="text-xs font-semibold text-slate-500 hover:underline">Kosongkan</button>
        </div>
    </div>

    <div class="grid max-h-72 gap-2 overflow-y-auto pr-1 sm:grid-cols-2">
        <template x-for="item in filtered" :key="item.id">
            <div class="flex items-center gap-3 rounded-xl border p-3 transition"
                 :class="has(item.id)
                    ? 'border-emerald-500 bg-emerald-50 shadow-sm dark:border-emerald-500 dark:bg-emerald-950/40'
                    : 'border-slate-200 bg-white hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900'">
                <button type="button" @click="toggle(item.id)"
                        class="flex min-w-0 flex-1 items-center gap-3 text-left">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md border-2 transition"
                          :class="has(item.id) ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-300 dark:border-slate-600'">
                        <svg x-show="has(item.id)" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.5-1.5z"/></svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-semibold" x-text="item.label"></span>
                        <span class="block truncate text-xs text-slate-500" x-text="item.sub" x-show="item.sub"></span>
                    </span>
                </button>

                @if ($pjName)
                    <button type="button" x-show="has(item.id)" x-cloak @click.stop="pj = item.id"
                            class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-bold transition"
                            :class="pj === item.id ? 'bg-amber-400 text-amber-950 shadow' : 'bg-slate-100 text-slate-600 hover:bg-amber-100 dark:bg-slate-800 dark:text-slate-300'">
                        <span x-text="pj === item.id ? '★ PJ' : 'Jadikan PJ'"></span>
                    </button>
                @endif
            </div>
        </template>
        <p x-show="filtered.length === 0" class="col-span-full py-6 text-center text-sm text-slate-500">Tidak ada hasil untuk "<span x-text="cari"></span>".</p>
    </div>

    {{-- Nilai yang dikirim ke server --}}
    <template x-for="id in selected" :key="'sel-' + id">
        <input type="hidden" name="{{ $name }}[]" :value="id">
    </template>
    @if ($pjName)
        <input type="hidden" name="{{ $pjName }}" :value="pj ?? ''">
    @endif
</div>

