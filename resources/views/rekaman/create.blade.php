@extends('layouts.app')
@section('title', 'Rekaman Baru')
@section('subtitle', 'Simpan sesi perekaman, tim peneliti, dan detail analisis dalam satu langkah')
@section('content')
    <form method="POST" action="{{ route('rekaman.store') }}" class="mx-auto max-w-5xl space-y-6">
        @csrf
        @include('rekaman._form')
        <div class="sticky bottom-4 flex justify-end gap-2 rounded-2xl border border-slate-200 bg-white/90 p-3 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
            <a href="{{ route('rekaman.index') }}" class="btn-ghost">Batal</a>
            <button class="btn-primary">Simpan rekaman</button>
        </div>
    </form>
@endsection
