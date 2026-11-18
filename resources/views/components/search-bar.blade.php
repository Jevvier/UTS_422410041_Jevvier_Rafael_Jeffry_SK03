@props(['filters' => [], 'placeholder' => 'Cari…'])
{{-- Pencarian langsung + filter. Kirim ulang form otomatis saat mengetik (debounce). --}}
<form method="GET" class="card flex flex-col gap-3 p-4 lg:flex-row lg:items-end" role="search">
    <div class="relative flex-1" x-data="liveSearch()">
        <label for="q" class="sr-only">Cari</label>
        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
        <input id="q" type="search" name="q" value="{{ $filters['q'] ?? request('q') }}"
               placeholder="{{ $placeholder ?? 'Cari…' }}"
               x-on:input="kirim($el)" class="field pl-9">
    </div>

    {{ $slot }}

    <div class="flex gap-2">
        <button type="submit" class="btn-primary">Terapkan</button>
        <a href="{{ url()->current() }}" class="btn-ghost">Reset</a>
    </div>
</form>
