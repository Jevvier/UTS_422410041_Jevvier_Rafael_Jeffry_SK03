@extends('layouts.app')
@section('title', 'Kategori Spesies')
@section('subtitle', 'Pengelompokan taksonomi untuk data spesies')

@section('content')
    @include('partials.page-header', [
        'title' => 'Kategori spesies',
        'subtitle' => 'Kelompok taksonomi yang digunakan pada data spesies.',
        'actions' => '<a href="'.route('kategori.create').'" class="btn-primary">+ Tambah kategori</a>',
    ])

    <x-search-bar placeholder="Cari nama kategori…" :filters="['q' => $q]" />

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-950/50">
                    <tr>
                        <th class="th">Nama</th>
                        <th class="th">Deskripsi</th>
                        <th class="th text-center">Jumlah spesies</th>
                        <th class="th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($items as $item)
                        <tr class="transition hover:bg-emerald-50/50 dark:hover:bg-slate-800/50">
                            <td class="td font-semibold">{{ $item->nama }}</td>
                            <td class="td max-w-md truncate">{{ $item->deskripsi ?: '—' }}</td>
                            <td class="td text-center">
                                <span class="rounded-full bg-teal-100 px-2.5 py-0.5 text-xs font-bold text-teal-800 dark:bg-teal-900/40 dark:text-teal-200">{{ $item->spesies_count }}</span>
                            </td>
                            <td class="td text-right whitespace-nowrap">
                                <a href="{{ route('kategori.edit', $item) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 dark:text-emerald-300 dark:hover:bg-emerald-950/50">Ubah</a>
                                <x-delete-button :action="route('kategori.destroy', $item)" pesan="Kategori '{{ $item->nama }}' akan dihapus. Kategori yang masih dipakai spesies tidak dapat dihapus." />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><x-empty-state judul="Kategori tidak ditemukan" deskripsi="Coba kata kunci lain atau tambahkan kategori baru." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('partials.pagination-info', ['items' => $items])
    </div>
@endsection
