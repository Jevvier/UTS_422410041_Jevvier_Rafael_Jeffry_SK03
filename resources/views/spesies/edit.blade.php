@extends('layouts.app')
@section('title', 'Ubah Spesies')
@section('subtitle', $spesies->nama_umum)
@section('content')
    <form method="POST" action="{{ route('spesies.update', $spesies) }}" class="mx-auto max-w-4xl space-y-6">
        @csrf
        @method('PUT')
        @include('spesies._form')
        <div class="flex justify-end gap-2">
            <a href="{{ route('spesies.show', $spesies) }}" class="btn-ghost">Batal</a>
            <button class="btn-primary">Perbarui spesies</button>
        </div>
    </form>
@endsection
