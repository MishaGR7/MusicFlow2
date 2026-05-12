@extends('layouts.app')

@section('content')
    <section class="mb-8 rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Release Catalog</h1>
                <p class="mt-2 text-sm text-slate-400">Browse all releases, filter by status or country, and focus on artists you follow.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('albums.index') }}" class="grid gap-4 md:grid-cols-4">
            <input name="q" value="{{ $filters['search'] }}" placeholder="Search by album or artist" class="music-input md:col-span-2" />
            <select name="status" class="music-input">
                <option value="">All statuses</option>
                @foreach($statuses as $value)
                    <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ ucfirst($value) }}</option>
                @endforeach
            </select>
            <select name="country" class="music-input">
                <option value="">All countries</option>
                @foreach($countries as $value)
                    <option value="{{ $value }}" @selected($filters['country'] === $value)>{{ $value }}</option>
                @endforeach
            </select>
            @auth
                <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-200 md:col-span-2">
                    <input type="checkbox" name="favorites" value="1" @checked($filters['onlyFavorites']) />
                    Only my favorite artists
                </label>
            @endauth
            <div class="flex flex-wrap gap-3 md:col-span-4">
                <button class="music-btn-primary" type="submit">Apply Filters</button>
                <a href="{{ route('albums.index') }}" class="music-btn-secondary">Reset</a>
            </div>
        </form>
    </section>

    <section class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($albums as $album)
            <article class="music-card">
                <img src="{{ $album->cover ? asset('storage/' . $album->cover) : 'https://placehold.co/800x800/0f172a/a78bfa?text=MusicFlow' }}" alt="{{ $album->title }}" class="h-56 w-full rounded-xl object-cover" />
                <div class="mt-4 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xl font-semibold text-white">{{ $album->title }}</p>
                        <p class="text-sm text-slate-400">
                            <a href="{{ route('artists.show', $album->artist) }}" class="hover:text-violet-300">{{ $album->artist->name }}</a>
                            · {{ $album->artist->country ?? 'Unknown' }}
                        </p>
                    </div>
                    <span class="music-badge music-badge-{{ $album->status }}">{{ strtoupper($album->status) }}</span>
                </div>
                <p class="mt-2 text-xs text-slate-500">Release: {{ $album->release_label }}</p>

                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('albums.show', $album) }}" class="music-btn-secondary">View</a>

                    @auth
                        <form method="POST" action="{{ route('favorites.toggle', $album->artist) }}">
                            @csrf
                            <button type="submit" class="music-btn-secondary">
                                {{ in_array($album->artist_id, $favoriteArtistIds, true) ? 'Remove Favorite' : 'Add Favorite' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="music-btn-secondary">Sign in to save</a>
                    @endauth
                </div>
            </article>
        @empty
            <p class="text-slate-400">No releases found for this filter set.</p>
        @endforelse
    </section>

    <div class="mt-8">{{ $albums->links() }}</div>
@endsection
