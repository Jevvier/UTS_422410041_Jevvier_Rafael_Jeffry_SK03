@extends('layouts.app')
@section('title', 'Ubah Peneliti')
@section('subtitle', $peneliti->nama)
@section('content')
    <form method="POST" action="{{ route('peneliti.update', $peneliti) }}" class="mx-auto max-w-3xl space-y-6">
        @csrf
        @method('PUT')
        @include('peneliti._form')
        <div class="flex justify-end gap-2"><a href="{{ route('peneliti.index') }}" class="btn-ghost">Batal</a><button class="btn-primary">Perbarui</button></div>
    </form>
@endsection
