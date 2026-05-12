@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-white">Manage Albums</h1>
        <a href="{{ route('admin.albums.create') }}" class="music-btn-primary">Add album</a>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach($albums as $album)
            <article class="music-card">
                <img src="{{ $album->cover ? asset('storage/' . $album->cover) : 'https://placehold.co/500x500/0f172a/a78bfa?text=Album' }}" class="h-48 w-full rounded-xl object-cover" alt="{{ $album->title ?: 'Untitled Album' }}" />
                <h2 class="mt-3 text-xl font-semibold text-white">{{ $album->title ?: 'Untitled Album' }}</h2>
                <p class="text-sm text-slate-400">{{ $album->artist->name }}</p>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('admin.albums.edit', $album) }}" class="music-btn-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.albums.destroy', $album) }}">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-lg border border-rose-700 px-3 py-2 text-sm text-rose-300 hover:bg-rose-900/20">Delete</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-8">{{ $albums->links() }}</div>
@endsection
