@extends('layouts.app')
@section('title', 'Ubah Rekaman')
@section('subtitle', $rekaman->spesies->nama_umum.' · '.$rekaman->tanggal_rekam->translatedFormat('d M Y'))
@section('content')
    <form method="POST" action="{{ route('rekaman.update', $rekaman) }}" class="mx-auto max-w-5xl space-y-6">
        @csrf
        @method('PUT')
        @include('rekaman._form')
        <div class="sticky bottom-4 flex justify-end gap-2 rounded-2xl border border-slate-200 bg-white/90 p-3 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
            <a href="{{ route('rekaman.show', $rekaman) }}" class="btn-ghost">Batal</a>
            <button class="btn-primary">Perbarui rekaman</button>
        </div>
    </form>
@endsection
