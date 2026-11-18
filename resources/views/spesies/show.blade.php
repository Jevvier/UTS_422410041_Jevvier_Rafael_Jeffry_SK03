@extends('layouts.app')
@section('title', $spesies->nama_umum)
@section('subtitle', $spesies->nama_ilmiah)
@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="card p-6 lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">{{ $spesies->kategori->nama }}</p>
                    <h2 class="mt-1 text-2xl font-extrabold">{{ $spesies->nama_umum }}</h2>
                    <p class="italic text-slate-500">{{ $spesies->nama_ilmiah }}</p>
                </div>
                <x-badge-konservasi :kode="$spesies->status_konservasi" />
            </div>
            <p class="mt-4 leading-relaxed text-slate-600 dark:text-slate-300">{{ $spesies->deskripsi ?: 'Belum ada deskripsi.' }}</p>

            <h3 class="mt-6 text-sm font-bold uppercase tracking-wider text-slate-500">Habitat</h3>
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach ($spesies->habitat as $h)
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-200">{{ $h->nama }}</span>
                @endforeach
            </div>
        </div>

        <div class="card space-y-4 p-6">
            <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-5 text-white">
                <p class="text-xs font-semibold text-emerald-100">Total rekaman</p>
                <p class="text-4xl font-extrabold tabular-nums">{{ $spesies->rekaman_count }}</p>
            </div>
            <a href="{{ route('spesies.edit', $spesies) }}" class="btn-ghost w-full">Ubah data</a>
            <a href="{{ route('rekaman.create', ['spesies_id' => $spesies->id]) }}" class="btn-primary w-full">+ Rekam untuk spesies ini</a>
            <x-delete-button :action="route('spesies.destroy', $spesies)" label="Hapus spesies" pesan="Spesies ini akan dihapus permanen jika belum memiliki rekaman." />
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"><h3 class="font-bold">10 rekaman terbaru</h3></div>
        <ul class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($rekaman as $r)
                <li>
                    <a href="{{ route('rekaman.show', $r) }}" class="flex items-center justify-between gap-4 px-5 py-3 transition hover:bg-emerald-50/50 dark:hover:bg-slate-800/50">
                        <span class="text-sm font-semibold">{{ $r->tanggal_rekam->translatedFormat('d M Y') }}</span>
                        <span class="text-sm text-slate-500">{{ $r->lokasi->nama }}</span>
                        <span class="text-sm tabular-nums">{{ $r->durasi_format }}</span>
                    </a>
                </li>
            @empty
                <li><x-empty-state judul="Belum ada rekaman" /></li>
            @endforelse
        </ul>
    </div>
@endsection
