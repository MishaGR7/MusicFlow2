@extends('layouts.app')

@section('content')
    <section class="grid gap-8 lg:grid-cols-[320px_minmax(0,1fr)]">
        <div class="music-card">
            <img src="{{ $artist->photo ? asset('storage/' . $artist->photo) : 'https://placehold.co/800x800/020617/a78bfa?text=Artist' }}" alt="{{ $artist->name }}" class="h-full w-full rounded-xl object-cover" />
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
            <p class="text-sm uppercase tracking-[0.3em] text-violet-300">{{ $artist->country ?: 'Unknown country' }}</p>
            <h1 class="mt-3 text-4xl font-bold text-white">{{ $artist->name }}</h1>
            <p class="mt-4 max-w-3xl text-slate-300">{{ $artist->bio ?: 'Biography will appear here after the artist is updated by an administrator.' }}</p>
            <p class="mt-4 text-sm text-slate-400">{{ $artist->followers->count() }} followers</p>

            @auth
                <form method="POST" action="{{ route('favorites.toggle', $artist) }}" class="mt-6">
                    @csrf
                    <button type="submit" class="music-btn-primary">Toggle favorite</button>
                </form>
            @endif
        </div>
    </section>

    <section class="mt-10">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-white">Releases by {{ $artist->name }}</h2>
            <p class="text-sm text-slate-400">This page exposes the one-to-many relation between artist and albums.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($artist->albums as $album)
                <article class="music-card">
                    <img src="{{ $album->cover ? asset('storage/' . $album->cover) : 'https://placehold.co/800x800/0f172a/a78bfa?text=Album' }}" alt="{{ $album->title }}" class="h-48 w-full rounded-xl object-cover" />
                    <div class="mt-4 flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-white">{{ $album->title }}</h3>
                            <p class="text-sm text-slate-400">{{ $album->release_label }}</p>
                        </div>
                        <span class="music-badge music-badge-{{ $album->status }}">{{ strtoupper($album->status) }}</span>
                    </div>
                    <a href="{{ route('albums.show', $album) }}" class="mt-4 inline-flex text-sm text-violet-300 hover:text-violet-200">Open release</a>
                </article>
            @empty
                <p class="text-slate-400">This artist has no releases yet.</p>
            @endforelse
        </div>
    </section>
@endsection
