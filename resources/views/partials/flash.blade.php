@php
    $pesan = [
        'success' => ['label' => 'Berhasil', 'kelas' => 'border-emerald-300 bg-emerald-50 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-200'],
        'error' => ['label' => 'Perhatian', 'kelas' => 'border-rose-300 bg-rose-50 text-rose-800 dark:border-rose-800 dark:bg-rose-950/60 dark:text-rose-200'],
    ];
@endphp

@foreach ($pesan as $jenis => $cfg)
    @if (session($jenis))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4500)"
             x-show="show" x-transition.opacity.duration.400ms
             class="flex items-start gap-3 rounded-xl border px-4 py-3 text-sm shadow-sm {{ $cfg['kelas'] }}" role="status">
            <div class="flex-1">
                <p class="font-semibold">{{ $cfg['label'] }}</p>
                <p>{{ session($jenis) }}</p>
            </div>
            <button type="button" @click="show = false" class="opacity-60 hover:opacity-100" aria-label="Tutup">✕</button>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-800 dark:bg-amber-950/50 dark:text-amber-200">
        <p class="font-semibold">Ada {{ $errors->count() }} isian yang perlu diperbaiki.</p>
    </div>
@endif
