@props(['label', 'value', 'hint' => null, 'tone' => 'emerald'])
@php
    $tones = [
        'emerald' => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-300',
        'teal' => 'bg-teal-500/15 text-teal-600 dark:text-teal-300',
        'amber' => 'bg-amber-500/15 text-amber-600 dark:text-amber-300',
        'rose' => 'bg-rose-500/15 text-rose-600 dark:text-rose-300',
        'sky' => 'bg-sky-500/15 text-sky-600 dark:text-sky-300',
    ];
@endphp
<div x-data="countUp({{ (int) $value }})"
     class="card group relative overflow-hidden p-5 transition duration-300 hover:-translate-y-0.5 hover:shadow-lg">
    <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full {{ $tones[$tone] ?? $tones['emerald'] }} opacity-60 blur-2xl transition group-hover:scale-125"></div>
    <p class="relative text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $label }}</p>
    <p class="relative mt-2 text-3xl font-extrabold tabular-nums" x-text="display.toLocaleString('id-ID')">{{ number_format((int) $value, 0, ',', '.') }}</p>
    @if ($hint)<p class="relative mt-1 text-xs text-slate-500">{{ $hint }}</p>@endif
</div>
