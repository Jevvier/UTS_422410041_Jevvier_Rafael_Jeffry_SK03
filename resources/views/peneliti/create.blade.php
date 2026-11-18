@extends('layouts.app')
@section('title', 'Tambah Peneliti')
@section('content')
    <form method="POST" action="{{ route('peneliti.store') }}" class="mx-auto max-w-3xl space-y-6">
        @csrf
        @include('peneliti._form')
        <div class="flex justify-end gap-2"><a href="{{ route('peneliti.index') }}" class="btn-ghost">Batal</a><button class="btn-primary">Simpan peneliti</button></div>
    </form>
@endsection
