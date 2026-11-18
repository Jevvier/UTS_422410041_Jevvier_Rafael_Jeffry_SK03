@extends('layouts.app')
@section('title', 'Lokasi')
@section('subtitle', 'Lokasi perekaman lapangan')
@section('content')
    @include('partials.page-header', [
        'title' => 'Lokasi perekaman',
        'subtitle' => 'Titik lapangan tempat rekaman dilakukan.',
        'actions' => '<a href="'.route('lokasi.create').'" class="btn-primary">+ Tambah lokasi</a>',
    ])

    <x-search-bar placeholder="Cari nama atau provinsi…" :filters="$filters">
        <select name="provinsi" class="field lg:w-56" aria-label="Filter provinsi">
            <option value="">Semua provinsi</option>
            @foreach ($daftarProvinsi as $p)
                <option value="{{ $p }}" @selected($filters['provinsi'] === $p)>{{ $p }}</option>
            @endforeach
        </select>
    </x-search-bar>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-950/50">
                    <tr>
                        <th class="th">Nama</th>
                        <th class="th">Provinsi</th>
                        <th class="th">Koordinat</th>
                        <th class="th text-center">Rekaman</th>
                        <th class="th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($items as $l)
                        <tr class="transition hover:bg-emerald-50/50 dark:hover:bg-slate-800/50">
                            <td class="td font-semibold">{{ $l->nama }}</td>
                            <td class="td">{{ $l->provinsi }}</td>
                            <td class="td font-mono text-xs">{{ $l->latitude !== null ? $l->latitude.', '.$l->longitude : '—' }}</td>
                            <td class="td text-center font-semibold">{{ $l->rekaman_count }}</td>
                            <td class="td text-right whitespace-nowrap">
                                <a href="{{ route('lokasi.edit', $l) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 dark:text-emerald-300 dark:hover:bg-emerald-950/50">Ubah</a>
                                <x-delete-button :action="route('lokasi.destroy', $l)" pesan="Lokasi '{{ $l->nama }}' akan dihapus. Lokasi yang memiliki rekaman tidak dapat dihapus." />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><x-empty-state judul="Lokasi tidak ditemukan" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('partials.pagination-info', ['items' => $items])
    </div>
@endsection
