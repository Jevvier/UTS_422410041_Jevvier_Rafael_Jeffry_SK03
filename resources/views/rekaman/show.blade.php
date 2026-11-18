@extends('layouts.app')
@section('title', 'Detail Rekaman')
@section('subtitle', $rekaman->spesies->nama_umum.' · '.$rekaman->lokasi->nama)
@section('content')
    @php
        $pj = $rekaman->peneliti->firstWhere('pivot.peran', 'Penanggung Jawab');
        $bars = range(0, 47);
    @endphp

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Pemutar visual (simulasi spektrogram; berkas audio tidak disimpan di basis data) --}}
        <div class="card relative overflow-hidden p-6 lg:col-span-2" x-data="{ main: false }">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">{{ $rekaman->spesies->kategori->nama }}</p>
                    <h2 class="mt-1 text-2xl font-extrabold">{{ $rekaman->spesies->nama_umum }}</h2>
                    <p class="italic text-slate-500">{{ $rekaman->spesies->nama_ilmiah }}</p>
                </div>
                <x-badge-konservasi :kode="$rekaman->spesies->status_konservasi" />
            </div>

            <div class="mt-6 rounded-2xl bg-gradient-to-b from-slate-900 to-slate-800 p-5">
                <div class="flex h-36 items-end justify-center gap-1" :class="main ? '' : 'opacity-60'">
                    @foreach ($bars as $b)
                        <span class="w-1.5 origin-bottom rounded-full bg-gradient-to-t from-emerald-500 to-teal-300 {{ $b % 3 === 0 ? 'animate-pulse' : '' }}"
                              :style="main ? 'animation: wave {{ 0.6 + ($b % 7) * 0.12 }}s ease-in-out infinite alternate; height: {{ 20 + (($b * 37) % 70) }}%' : 'height: {{ 10 + (($b * 37) % 40) }}%'"></span>
                    @endforeach
                </div>
                <div class="mt-4 flex items-center justify-between text-white">
                    <button type="button" @click="main = !main"
                            class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/40 transition hover:scale-105 active:scale-95"
                            :aria-label="main ? 'Jeda' : 'Putar'">
                        <svg x-show="!main" class="ml-0.5 h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                        <svg x-show="main" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                    </button>
                    <span class="font-mono text-sm tabular-nums">{{ $rekaman->durasi_format }} · {{ number_format($rekaman->sample_rate, 0, ',', '.') }} Hz · {{ $rekaman->format }}</span>
                </div>
            </div>

            <dl class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div><dt class="text-xs font-semibold uppercase text-slate-500">Tanggal</dt><dd class="font-semibold">{{ $rekaman->tanggal_rekam->translatedFormat('l, d F Y') }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase text-slate-500">Waktu mulai</dt><dd class="font-semibold">{{ $rekaman->waktu_mulai ? substr($rekaman->waktu_mulai, 0, 5).' WIB' : '—' }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase text-slate-500">Kondisi cuaca</dt><dd class="font-semibold">{{ $rekaman->kondisi_cuaca ?: '—' }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase text-slate-500">Lokasi</dt><dd class="font-semibold">{{ $rekaman->lokasi->nama }}<span class="block text-xs font-normal text-slate-500">{{ $rekaman->lokasi->provinsi }}</span></dd></div>
                <div><dt class="text-xs font-semibold uppercase text-slate-500">Kualitas</dt><dd class="font-semibold">{{ $rekaman->kualitas }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase text-slate-500">Penanggung jawab</dt><dd class="font-semibold">{{ $pj?->nama ?? '—' }}</dd></div>
            </dl>
            @if ($rekaman->catatan)
                <div class="mt-6 rounded-xl bg-amber-50 p-4 text-sm text-amber-900 dark:bg-amber-950/40 dark:text-amber-200">
                    <p class="font-semibold">Catatan lapangan</p>
                    <p class="mt-1">{{ $rekaman->catatan }}</p>
                </div>
            @endif
        </div>

        <aside class="space-y-6">
            <div class="card p-6">
                <h3 class="font-bold">Tim peneliti</h3>
                <ul class="mt-4 space-y-3">
                    @foreach ($rekaman->peneliti as $p)
                        <li class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 text-sm font-bold text-white">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($p->nama, 0, 1)) }}</span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold">{{ $p->nama }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $p->institusi ?: $p->email }}</p>
                            </div>
                            @if ($p->pivot->peran === 'Penanggung Jawab')
                                <span class="rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-bold text-amber-950">★ PJ</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="card space-y-3 p-6">
                <a href="{{ route('rekaman.edit', $rekaman) }}" class="btn-primary w-full">Ubah rekaman</a>
                <x-delete-button :action="route('rekaman.destroy', $rekaman)" label="Hapus rekaman" pesan="Rekaman ini beserta detail analisis akan dihapus permanen." />
            </div>
        </aside>
    </div>

    <div class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">
            <h3 class="font-bold">Detail analisis</h3>
            <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-bold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">{{ $rekaman->analisis->count() }} entri</span>
        </div>
        @if ($rekaman->analisis->isEmpty())
            <x-empty-state judul="Belum ada detail analisis" />
        @else
            <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($rekaman->analisis as $a)
                    <div class="rounded-2xl border border-slate-200 p-4 transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800"
                         x-data="{ w: 0 }" x-init="setTimeout(() => w = {{ (float) $a->tingkat_keyakinan }}, 150)">
                        <p class="text-sm font-bold">{{ $a->jenis_analisis }}</p>
                        <p class="mt-1 text-xs text-slate-500">Frekuensi dominan: <span class="font-mono font-semibold">{{ $a->frekuensi_dominan_hz !== null ? number_format($a->frekuensi_dominan_hz, 2, ',', '.').' Hz' : '—' }}</span></p>
                        <div class="mt-3">
                            <div class="flex justify-between text-xs font-semibold"><span>Tingkat keyakinan</span><span x-text="Math.round(w) + '%'"></span></div>
                            <div class="mt-1 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 transition-[width] duration-1000 ease-out" :style="`width: ${w}%`"></div>
                            </div>
                        </div>
                        @if ($a->hasil)<p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $a->hasil }}</p>@endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('head')
<style>
    @keyframes wave { from { transform: scaleY(.35); } to { transform: scaleY(1); } }
</style>
@endpush
