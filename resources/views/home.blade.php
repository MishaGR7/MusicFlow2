@extends('layouts.app')

@section('content')
    <section class="mb-8 overflow-hidden rounded-3xl border border-slate-800 bg-gradient-to-br from-slate-900 via-slate-950 to-violet-950/80 p-8">
        <p class="text-sm uppercase tracking-[0.3em] text-violet-300">MusicFlow</p>
        <h1 class="mt-4 max-w-3xl text-4xl font-bold text-white md:text-5xl">Track releases, follow artists, and keep your music portfolio project polished.</h1>
        <p class="mt-4 max-w-2xl text-base text-slate-300">This is the navigation hub of the application: discover releases, browse artists, manage your favorites, and keep profile settings in one place.</p>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('albums.index') }}" class="music-btn-primary">Releases</a>
            <a href="{{ route('artists.index') }}" class="music-btn-secondary">Artists</a>
            @auth
                <a href="{{ route('profile.edit') }}" class="music-btn-secondary">Open Profile</a>
            @else
                <a href="{{ route('register') }}" class="music-btn-secondary">Create Account</a>
            @endauth
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-800 bg-black/20 p-4">
                <p class="text-sm text-slate-400">Artists</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['artists'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-black/20 p-4">
                <p class="text-sm text-slate-400">Releases</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['albums'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-black/20 p-4">
                <p class="text-sm text-slate-400">Published</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['published'] }}</p>
            </div>
        </div>
    </section>

    <section class="mb-10">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">Latest Releases</h2>
                <p class="text-sm text-slate-400">A quick preview from the release catalog.</p>
            </div>
            <a href="{{ route('albums.index') }}" class="music-btn-secondary">See all releases</a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($latestAlbums as $album)
                <article class="music-card">
                    <img src="{{ $album->cover ? asset('storage/' . $album->cover) : 'https://placehold.co/800x800/0f172a/a78bfa?text=MusicFlow' }}" alt="{{ $album->title }}" class="h-56 w-full rounded-xl object-cover" />
                    <div class="mt-4 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xl font-semibold text-white">{{ $album->title }}</p>
                            <p class="text-sm text-slate-400">{{ $album->artist->name }} · {{ $album->release_label }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $album->tracks_count }} tracks</p>
                        </div>
                        <span class="music-badge music-badge-{{ $album->status }}">{{ strtoupper($album->status) }}</span>
                    </div>
                    <a href="{{ route('albums.show', $album) }}" class="mt-4 inline-flex text-sm text-violet-300 hover:text-violet-200">Open release</a>
                </article>
            @empty
                <p class="text-slate-400">No releases yet.</p>
            @endforelse
        </div>
    </section>

    <section>
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">Featured Artists</h2>
                <p class="text-sm text-slate-400">Public artist pages now show their release history.</p>
            </div>
            <a href="{{ route('artists.index') }}" class="music-btn-secondary">See all artists</a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($featuredArtists as $artist)
                <article class="music-card">
                    <img src="{{ $artist->photo ? asset('storage/' . $artist->photo) : 'https://placehold.co/800x800/020617/a78bfa?text=Artist' }}" alt="{{ $artist->name }}" class="h-56 w-full rounded-xl object-cover" />
                    <h3 class="mt-4 text-xl font-semibold text-white">{{ $artist->name }}</h3>
                    <p class="mt-1 text-sm text-slate-400">{{ $artist->country ?: 'Unknown country' }} · {{ $artist->company ?: 'No company listed' }}</p>
                    <p class="mt-3 text-sm text-slate-300">{{ $artist->albums_count }} releases · {{ $artist->followers_count }} followers</p>
                    <a href="{{ route('artists.show', $artist) }}" class="mt-4 inline-flex text-sm text-violet-300 hover:text-violet-200">Open artist page</a>
                </article>
            @empty
                <p class="text-slate-400">No artists yet.</p>
            @endforelse
        </div>
    </section>
@endsection
