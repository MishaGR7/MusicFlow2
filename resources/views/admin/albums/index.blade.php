@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-white">Manage Albums</h1>
        <a href="{{ route('admin.albums.create') }}" class="music-btn-primary">Add album</a>
    </div>

    <form method="GET" action="{{ route('admin.albums.index') }}" class="mb-6 grid gap-4 rounded-2xl border border-slate-800 bg-slate-900/70 p-4 md:grid-cols-4">
        <input name="q" value="{{ $filters['search'] }}" placeholder="Search by album or artist" class="music-input md:col-span-2" />
        <select name="status" class="music-input">
            <option value="">All statuses</option>
            @foreach($statuses as $value)
                <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ ucfirst($value) }}</option>
            @endforeach
        </select>
        <select name="artist_id" class="music-input">
            <option value="">All artists</option>
            @foreach($artists as $artist)
                <option value="{{ $artist->id }}" @selected($filters['artistId'] === $artist->id)>{{ $artist->name }}</option>
            @endforeach
        </select>
        <div class="flex flex-wrap gap-3 md:col-span-4">
            <button class="music-btn-primary" type="submit">Filter</button>
            <a href="{{ route('admin.albums.index') }}" class="music-btn-secondary">Reset</a>
        </div>
    </form>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($albums as $album)
            <article class="music-card">
                <img src="{{ $album->cover ? asset('storage/' . $album->cover) : 'https://placehold.co/500x500/0f172a/a78bfa?text=Album' }}" class="h-48 w-full rounded-xl object-cover" alt="{{ $album->title ?: 'Untitled Album' }}" />
                <h2 class="mt-3 text-xl font-semibold text-white">{{ $album->title ?: 'Untitled Album' }}</h2>
                <p class="text-sm text-slate-400">{{ $album->artist->name }}</p>
                <p class="mt-2 text-sm text-slate-400">Status: {{ ucfirst($album->status) }}</p>
                <p class="mt-2 text-sm text-slate-300">{{ $album->tracks_count }} tracks</p>
                <p class="text-sm text-slate-400">Title track: {{ $album->titleTrack->title ?? 'Not selected' }}</p>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('admin.albums.edit', $album) }}" class="music-btn-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.albums.destroy', $album) }}" onsubmit="return confirm('Delete this album? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-lg border border-rose-700 px-3 py-2 text-sm text-rose-300 hover:bg-rose-900/20">Delete</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="text-slate-400">No albums found for this filter set.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $albums->links() }}</div>
@endsection
