@props(['kode'])
@php
    $kelas = [
        'LC' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
        'NT' => 'bg-lime-100 text-lime-800 dark:bg-lime-900/40 dark:text-lime-200',
        'VU' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
        'EN' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-200',
        'CR' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200',
        'DD' => 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200',
    ];
@endphp
<span title="{{ \App\Models\Spesies::STATUS_KONSERVASI[$kode] ?? $kode }}"
      class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $kelas[$kode] ?? $kelas['DD'] }}">{{ $kode }}</span>
