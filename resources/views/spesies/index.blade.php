@extends('layouts.app')
@section('title', 'Spesies')
@section('subtitle', 'Data utama spesies hewan yang direkam')

@section('content')
    @include('partials.page-header', [
        'title' => 'Daftar spesies',
        'subtitle' => 'Cari dan saring spesies berdasarkan kategori dan status konservasi.',
        'actions' => '<a href="'.route('spesies.create').'" class="btn-primary">+ Tambah spesies</a>',
    ])

    <x-search-bar placeholder="Cari nama umum atau ilmiah…" :filters="$filters">
        <div class="grid grid-cols-2 gap-3 lg:w-96">
            <select name="kategori_spesies_id" class="field" aria-label="Filter kategori">
                <option value="">Semua kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" @selected(($filters['kategori_spesies_id'] ?? '') == $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
            <select name="status_konservasi" class="field" aria-label="Filter status konservasi">
                <option value="">Semua status</option>
                @foreach ($statusKonservasi as $kode => $label)
                    <option value="{{ $kode }}" @selected(($filters['status_konservasi'] ?? '') === $kode)>{{ $kode }} — {{ \Illuminate\Support\Str::before($label, ' (') }}</option>
                @endforeach
            </select>
        </div>
    </x-search-bar>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-950/50">
                    <tr>
                        <th class="th">Nama umum</th>
                        <th class="th">Nama ilmiah</th>
                        <th class="th">Kategori</th>
                        <th class="th">Habitat</th>
                        <th class="th text-center">Konservasi</th>
                        <th class="th text-center">Rekaman</th>
                        <th class="th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($items as $s)
                        <tr class="transition hover:bg-emerald-50/50 dark:hover:bg-slate-800/50">
                            <td class="td font-semibold"><a href="{{ route('spesies.show', $s) }}" class="hover:text-emerald-700 dark:hover:text-emerald-300">{{ $s->nama_umum }}</a></td>
                            <td class="td italic text-slate-500">{{ $s->nama_ilmiah }}</td>
                            <td class="td">{{ $s->kategori->nama }}</td>
                            <td class="td">
                                <div class="flex max-w-xs flex-wrap gap-1">
                                    @foreach ($s->habitat->take(3) as $h)
                                        <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-medium dark:bg-slate-800">{{ $h->nama }}</span>
                                    @endforeach
                                    @if ($s->habitat->count() > 3)<span class="text-[11px] text-slate-500">+{{ $s->habitat->count() - 3 }}</span>@endif
                                </div>
                            </td>
                            <td class="td text-center"><x-badge-konservasi :kode="$s->status_konservasi" /></td>
                            <td class="td text-center font-semibold">{{ $s->rekaman_count }}</td>
                            <td class="td text-right whitespace-nowrap">
                                <a href="{{ route('spesies.edit', $s) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 dark:text-emerald-300 dark:hover:bg-emerald-950/50">Ubah</a>
                                <x-delete-button :action="route('spesies.destroy', $s)" pesan="Spesies '{{ $s->nama_umum }}' akan dihapus. Spesies yang sudah memiliki rekaman tidak dapat dihapus." />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-empty-state judul="Tidak ada spesies yang cocok" deskripsi="Ubah filter atau tambahkan spesies baru." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('partials.pagination-info', ['items' => $items])
    </div>
@endsection
