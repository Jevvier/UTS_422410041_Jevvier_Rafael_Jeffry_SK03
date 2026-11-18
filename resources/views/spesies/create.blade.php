@extends('layouts.app')
@section('title', 'Tambah Spesies')
@section('subtitle', 'Daftarkan spesies baru beserta habitatnya')
@section('content')
    <form method="POST" action="{{ route('spesies.store') }}" class="mx-auto max-w-4xl space-y-6">
        @csrf
        @include('spesies._form')
        <div class="flex justify-end gap-2">
            <a href="{{ route('spesies.index') }}" class="btn-ghost">Batal</a>
            <button class="btn-primary">Simpan spesies</button>
        </div>
    </form>
@endsection
