@php
    $menu = [
        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z', 'pattern' => 'dashboard'],
        ['route' => 'rekaman.index', 'label' => 'Rekaman', 'icon' => 'M12 14a3 3 0 003-3V5a3 3 0 00-6 0v6a3 3 0 003 3zm5-3a5 5 0 01-10 0H5a7 7 0 006 6.92V21h2v-3.08A7 7 0 0019 11h-2z', 'pattern' => 'rekaman.*'],
        ['route' => 'spesies.index', 'label' => 'Spesies', 'icon' => 'M12 2a10 10 0 100 20 10 10 0 000-20zm0 3a7 7 0 110 14 7 7 0 010-14z', 'pattern' => 'spesies.*'],
        ['route' => 'kategori.index', 'label' => 'Kategori', 'icon' => 'M4 4h7v7H4V4zm9 0h7v7h-7V4zM4 13h7v7H4v-7zm9 0h7v7h-7v-7z', 'pattern' => 'kategori.*'],
        ['route' => 'lokasi.index', 'label' => 'Lokasi', 'icon' => 'M12 2a7 7 0 00-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 00-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z', 'pattern' => 'lokasi.*'],
        ['route' => 'peneliti.index', 'label' => 'Peneliti', 'icon' => 'M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z', 'pattern' => 'peneliti.*'],
    ];
@endphp

<aside class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform border-r border-slate-200 bg-white transition-transform duration-300 lg:static lg:translate-x-0 dark:border-slate-800 dark:bg-slate-900"
       :class="sidebar ? 'translate-x-0' : ''">
    <div class="flex h-16 items-center gap-3 border-b border-slate-200 px-6 dark:border-slate-800">
        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 text-white shadow-lg shadow-emerald-500/30">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 12h2v4H3zm4-4h2v12H7zm4-3h2v18h-2zm4 4h2v10h-2zm4-2h2v12h-2z"/></svg>
        </div>
        <div>
            <p class="text-sm font-extrabold leading-tight">Bioakustik</p>
            <p class="text-[11px] font-medium text-slate-500">SK03 · Laboratorium</p>
        </div>
    </div>

    <nav class="space-y-1 p-4">
        @foreach ($menu as $item)
            @php $aktif = request()->routeIs($item['pattern']); @endphp
            <a href="{{ route($item['route']) }}"
               @click="sidebar = false"
               class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition
                      {{ $aktif ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                <svg class="h-5 w-5 transition group-hover:scale-110" viewBox="0 0 24 24" fill="currentColor"><path d="{{ $item['icon'] }}"/></svg>
                {{ $item['label'] }}
                @if ($aktif)<span class="ml-auto h-2 w-2 rounded-full bg-emerald-500"></span>@endif
            </a>
        @endforeach
    </nav>

    <div class="absolute bottom-0 w-full border-t border-slate-200 p-4 dark:border-slate-800">
        <div class="rounded-xl bg-gradient-to-br from-emerald-500/10 to-teal-500/10 p-3 text-xs text-slate-600 dark:text-slate-300">
            <p class="font-semibold">Tips</p>
            <p class="mt-1">Gunakan kolom pencarian untuk memfilter data secara langsung.</p>
        </div>
    </div>
</aside>

<div x-show="sidebar" x-cloak @click="sidebar = false" class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm lg:hidden"></div>
