@extends('layouts.app')
@section('title', 'Tambah Lokasi')
@section('content')
    <form method="POST" action="{{ route('lokasi.store') }}" class="mx-auto max-w-3xl space-y-6">
        @csrf
        @include('lokasi._form')
        <div class="flex justify-end gap-2"><a href="{{ route('lokasi.index') }}" class="btn-ghost">Batal</a><button class="btn-primary">Simpan lokasi</button></div>
    </form>
@endsection
