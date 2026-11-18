@extends('layouts.app')
@section('title', 'Ubah Lokasi')
@section('subtitle', $lokasi->nama)
@section('content')
    <form method="POST" action="{{ route('lokasi.update', $lokasi) }}" class="mx-auto max-w-3xl space-y-6">
        @csrf
        @method('PUT')
        @include('lokasi._form')
        <div class="flex justify-end gap-2"><a href="{{ route('lokasi.index') }}" class="btn-ghost">Batal</a><button class="btn-primary">Perbarui</button></div>
    </form>
@endsection
