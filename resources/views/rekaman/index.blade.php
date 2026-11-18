@extends('layouts.app')
@section('title', 'Rekaman')
@section('subtitle', 'Data utama sesi perekaman lapangan')

@section('content')
    @php
        $totalDetik = $ringkasan['total_durasi'];
        $jam = intdiv($totalDetik, 3600);
        $menit = intdiv($totalDetik % 3600, 60);
    @endphp

    @include('partials.page-header', [
        'title' => 'Daftar rekaman',
        'subtitle' => 'Telusuri sesi rekaman berdasarkan spesies, lokasi, peneliti, kualitas, dan rentang tanggal.',
        'actions' => '<a href="'.route('rekaman.create').'" class="btn-primary">+ Rekaman baru</a>',
    ])

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 14a3 3 0 003-3V5a3 3 0 00-6 0v6a3 3 0 003 3zm5-3a5 5 0 01-10 0H5a7 7 0 006 6.92V21h2v-3.08A7 7 0 0019 11h-2z"/></svg>
            </div>
            <div><p class="text-xs font-semibold uppercase text-slate-500">Hasil filter</p><p class="text-2xl font-extrabold tabular-nums">{{ number_format($ringkasan['total'], 0, ',', '.') }}</p></div>
        </div>
        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-100 text-teal-700 dark:bg-teal-950 dark:text-teal-300">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 11V7h-2v7h6v-2h-4z"/></svg>
            </div>
            <div><p class="text-xs font-semibold uppercase text-slate-500">Total durasi</p><p class="text-2xl font-extrabold tabular-nums">{{ $jam }}j {{ $menit }}m</p></div>
        </div>
        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.55A4 4 0 1014 17V7h4V3h-6z"/></svg>
            </div>
            <div><p class="text-xs font-semibold uppercase text-slate-500">Halaman</p><p class="text-2xl font-extrabold tabular-nums">{{ $items->currentPage() }} / {{ max($items->lastPage(), 1) }}</p></div>
        </div>
    </div>

    <x-search-bar placeholder="Cari spesies, lokasi, cuaca, atau catatan…" :filters="$filters">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5 lg:flex-1">
            <select name="spesies_id" class="field" aria-label="Filter spesies">
                <option value="">Semua spesies</option>
                @foreach ($spesies as $s)
                    <option value="{{ $s->id }}" @selected(($filters['spesies_id'] ?? '') == $s->id)>{{ $s->nama_umum }}</option>
                @endforeach
            </select>
            <select name="lokasi_id" class="field" aria-label="Filter lokasi">
                <option value="">Semua lokasi</option>
                @foreach ($lokasi as $l)
                    <option value="{{ $l->id }}" @selected(($filters['lokasi_id'] ?? '') == $l->id)>{{ $l->nama }}</option>
                @endforeach
            </select>
            <select name="peneliti_id" class="field" aria-label="Filter peneliti">
                <option value="">Semua peneliti</option>
                @foreach ($peneliti as $p)
                    <option value="{{ $p->id }}" @selected(($filters['peneliti_id'] ?? '') == $p->id)>{{ $p->nama }}</option>
                @endforeach
            </select>
            <select name="kualitas" class="field" aria-label="Filter kualitas">
                <option value="">Semua kualitas</option>
                @foreach ($kualitas as $k)
                    <option value="{{ $k }}" @selected(($filters['kualitas'] ?? '') === $k)>{{ $k }}</option>
                @endforeach
            </select>
            <div class="grid grid-cols-2 gap-2">
                <input type="date" name="dari" value="{{ $filters['dari'] ?? '' }}" class="field" aria-label="Dari tanggal" title="Dari tanggal">
                <input type="date" name="sampai" value="{{ $filters['sampai'] ?? '' }}" class="field" aria-label="Sampai tanggal" title="Sampai tanggal">
            </div>
        </div>
    </x-search-bar>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-950/50">
                    <tr>
                        <th class="th">Tanggal</th>
                        <th class="th">Spesies</th>
                        <th class="th">Lokasi</th>
                        <th class="th text-right">Durasi</th>
                        <th class="th">Kualitas</th>
                        <th class="th">Peneliti</th>
                        <th class="th text-center">Analisis</th>
                        <th class="th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($items as $r)
                        @php
                            $kelasKualitas = match ($r->kualitas) {
                                'Baik' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
                                'Sedang' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                                default => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200',
                            };
                        @endphp
                        <tr class="group transition hover:bg-emerald-50/50 dark:hover:bg-slate-800/50">
                            <td class="td whitespace-nowrap font-medium">{{ $r->tanggal_rekam->translatedFormat('d M Y') }}
                                @if ($r->waktu_mulai)<span class="block text-xs text-slate-500">{{ substr($r->waktu_mulai, 0, 5) }} WIB</span>@endif
                            </td>
                            <td class="td">
                                <a href="{{ route('rekaman.show', $r) }}" class="font-semibold hover:text-emerald-700 dark:hover:text-emerald-300">{{ $r->spesies->nama_umum }}</a>
                                <span class="block text-xs italic text-slate-500">{{ $r->spesies->nama_ilmiah }}</span>
                            </td>
                            <td class="td">{{ $r->lokasi->nama }}<span class="block text-xs text-slate-500">{{ $r->lokasi->provinsi }}</span></td>
                            <td class="td text-right font-mono tabular-nums">{{ $r->durasi_format }}</td>
                            <td class="td"><span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $kelasKualitas }}">{{ $r->kualitas }}</span></td>
                            <td class="td">
                                <div class="flex -space-x-2">
                                    @foreach ($r->peneliti->take(3) as $p)
                                        <span title="{{ $p->nama }}" class="flex h-7 w-7 items-center justify-center rounded-full border-2 border-white bg-gradient-to-br from-emerald-400 to-teal-600 text-[10px] font-bold text-white dark:border-slate-900">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($p->nama, 0, 1)) }}</span>
                                    @endforeach
                                    @if ($r->peneliti->count() > 3)
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full border-2 border-white bg-slate-200 text-[10px] font-bold dark:border-slate-900 dark:bg-slate-700">+{{ $r->peneliti->count() - 3 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="td text-center font-semibold">{{ $r->analisis_count }}</td>
                            <td class="td whitespace-nowrap text-right">
                                <a href="{{ route('rekaman.show', $r) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Detail</a>
                                <a href="{{ route('rekaman.edit', $r) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 dark:text-emerald-300 dark:hover:bg-emerald-950/50">Ubah</a>
                                <x-delete-button :action="route('rekaman.destroy', $r)" pesan="Rekaman tanggal {{ $r->tanggal_rekam->format('d/m/Y') }} beserta detail analisisnya akan dihapus." />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><x-empty-state judul="Belum ada rekaman yang cocok" deskripsi="Coba hapus sebagian filter, atau buat rekaman baru." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('partials.pagination-info', ['items' => $items])
    </div>
@endsection
