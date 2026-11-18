@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('subtitle', 'Buat kategori spesies baru')
@section('content')
    <form method="POST" action="{{ route('kategori.store') }}" class="mx-auto max-w-2xl space-y-6">
        @csrf
        @include('kategori._form')
        <div class="flex justify-end gap-2">
            <a href="{{ route('kategori.index') }}" class="btn-ghost">Batal</a>
            <button class="btn-primary">Simpan kategori</button>
        </div>
    </form>
@endsection
