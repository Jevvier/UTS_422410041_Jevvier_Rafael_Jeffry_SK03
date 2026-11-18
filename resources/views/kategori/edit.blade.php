@extends('layouts.app')
@section('title', 'Ubah Kategori')
@section('subtitle', $kategori->nama)
@section('content')
    <form method="POST" action="{{ route('kategori.update', $kategori) }}" class="mx-auto max-w-2xl space-y-6">
        @csrf
        @method('PUT')
        @include('kategori._form')
        <div class="flex justify-end gap-2">
            <a href="{{ route('kategori.index') }}" class="btn-ghost">Batal</a>
            <button class="btn-primary">Perbarui</button>
        </div>
    </form>
@endsection
