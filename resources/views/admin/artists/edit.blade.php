@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-3xl font-bold text-white">Edit Artist</h1>
    <form method="POST" action="{{ route('admin.artists.update', $artist) }}" enctype="multipart/form-data" class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
        @method('PUT')
        @include('admin.artists._form')
    </form>
@endsection
