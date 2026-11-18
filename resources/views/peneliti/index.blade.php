@extends('layouts.app')
@section('title', 'Peneliti')
@section('subtitle', 'Anggota tim peneliti laboratorium')
@section('content')
    @include('partials.page-header', [
        'title' => 'Peneliti',
        'subtitle' => 'Daftar peneliti yang terlibat dalam perekaman.',
        'actions' => '<a href="'.route('peneliti.create').'" class="btn-primary">+ Tambah peneliti</a>',
    ])

    <x-search-bar placeholder="Cari nama, email, atau institusi…" :filters="['q' => $q]" />

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($items as $p)
            <div class="card group p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 text-lg font-extrabold text-white shadow">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($p->nama, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-bold">{{ $p->nama }}</p>
                        <p class="truncate text-xs text-slate-500">{{ $p->email }}</p>
                        <p class="mt-1 truncate text-xs font-medium text-emerald-700 dark:text-emerald-300">{{ $p->keahlian ?: 'Keahlian belum diisi' }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-sm dark:border-slate-800">
                    <span class="text-slate-500">{{ $p->institusi ?: '—' }}</span>
                    <span class="font-semibold">{{ $p->rekaman_count }} rekaman</span>
                </div>
                <div class="mt-3 flex justify-end gap-1">
                    <a href="{{ route('peneliti.edit', $p) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 dark:text-emerald-300 dark:hover:bg-emerald-950/50">Ubah</a>
                    <x-delete-button :action="route('peneliti.destroy', $p)" pesan="Peneliti '{{ $p->nama }}' akan dihapus dari seluruh rekaman terkait." />
                </div>
            </div>
        @empty
            <div class="card sm:col-span-2 xl:col-span-3"><x-empty-state judul="Peneliti tidak ditemukan" /></div>
        @endforelse
    </div>
    <div class="card">@include('partials.pagination-info', ['items' => $items])</div>
@endsection
