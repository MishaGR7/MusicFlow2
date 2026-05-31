@extends('layouts.app')

@section('content')
    <article class="grid gap-8 lg:grid-cols-[320px_minmax(0,1fr)]">
        <div class="music-detail-media">
            <div class="music-detail-image-frame">
                <img src="{{ $album->cover ? asset('storage/' . $album->cover) : 'https://placehold.co/800x800/0f172a/a78bfa?text=Album' }}" alt="{{ $album->title }}" class="music-detail-image" />
            </div>
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
            <p class="text-sm uppercase tracking-[0.3em] text-violet-300">{{ strtoupper($album->status) }}</p>
            <h1 class="mt-3 text-4xl font-bold text-white">{{ $album->title }}</h1>
            <p class="mt-3 text-lg text-slate-300">
                by <a href="{{ route('artists.show', $album->artist) }}" class="text-violet-300 hover:text-violet-200">{{ $album->artist->name }}</a>
            </p>
            <p class="mt-2 text-sm text-slate-400">Release date: {{ $album->release_label }}</p>
            <p class="mt-2 text-sm text-slate-400">Country: {{ $album->artist->country ?? 'Unknown' }}</p>
            <p class="mt-2 text-sm text-slate-400">Tracks: {{ $album->tracks->count() }}</p>

            @if($album->spotify_url)
                <a href="{{ $album->spotify_url }}" target="_blank" rel="noopener" class="music-btn-secondary mt-6 inline-flex">Open on Spotify</a>
            @endif

            @auth
                <form method="POST" action="{{ route('favorites.toggle', $album->artist) }}" class="mt-6">
                    @csrf
                    <button type="submit" class="music-btn-primary">Toggle artist in favorites</button>
                </form>
            @endif
        </div>
    </article>

    <section class="mt-10 rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
        <h2 class="text-2xl font-bold text-white">Track list</h2>

        <div class="mt-5 divide-y divide-slate-800">
            @forelse($album->tracks as $track)
                <div class="grid gap-3 py-3 text-sm md:grid-cols-[56px_minmax(0,1fr)_120px_140px] md:items-center">
                    <p class="font-semibold text-slate-500">#{{ $track->position }}</p>
                    <p class="text-slate-100">{{ $track->title }}</p>
                    <p class="text-slate-400">{{ $track->duration ?: '-' }}</p>
                    @if($track->is_title_track)
                        <span class="w-fit rounded-full bg-violet-900/50 px-3 py-1 text-xs font-semibold text-violet-200">Title track</span>
                    @endif
                </div>
            @empty
                <p class="mt-4 text-slate-400">Track list has not been added yet.</p>
            @endforelse
        </div>
    </section>
@endsection
