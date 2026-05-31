@extends('layouts.app')

@section('content')
    <section class="grid gap-8 lg:grid-cols-[320px_minmax(0,1fr)]">
        <div class="music-detail-media">
            <div class="music-detail-image-frame">
                <img src="{{ $artist->photo ? asset('storage/' . $artist->photo) : 'https://placehold.co/800x800/020617/a78bfa?text=Artist' }}" alt="{{ $artist->name }}" class="music-detail-image" />
            </div>
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
            <p class="text-sm uppercase tracking-[0.3em] text-violet-300">{{ $artist->country ?: 'Unknown country' }} · {{ ucfirst($artist->artist_type ?? 'group') }}</p>
            <h1 class="mt-3 text-4xl font-bold text-white">{{ $artist->name }}</h1>
            <p class="mt-4 max-w-3xl text-slate-300">{{ $artist->bio ?: 'Biography will appear here after the artist is updated by an administrator.' }}</p>

            <dl class="mt-6 grid gap-4 text-sm md:grid-cols-2">
                <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                    <dt class="text-slate-500">Company</dt>
                    <dd class="mt-1 text-slate-200">{{ $artist->company ?: 'Unknown' }}</dd>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                    <dt class="text-slate-500">Debut</dt>
                    <dd class="mt-1 text-slate-200">{{ $artist->debut_date ? $artist->debut_date->format('d M Y') : 'Unknown' }}</dd>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                    <dt class="text-slate-500">Members</dt>
                    <dd class="mt-1 text-slate-200">{{ $artist->members_count ?: 'Unknown' }}</dd>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                    <dt class="text-slate-500">Fandom</dt>
                    <dd class="mt-1 text-slate-200">{{ $artist->fandom_name ?: 'Unknown' }}</dd>
                </div>
            </dl>

            <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-slate-400">
                <p>{{ $artist->followers->count() }} followers</p>
                @if($artist->official_site)
                    <a href="{{ $artist->official_site }}" target="_blank" rel="noopener" class="text-violet-300 hover:text-violet-200">Official site</a>
                @endif
                @if($artist->spotify_url)
                    <a href="{{ $artist->spotify_url }}" target="_blank" rel="noopener" class="text-violet-300 hover:text-violet-200">Spotify</a>
                @endif
                @if($artist->instagram_url)
                    <a href="{{ $artist->instagram_url }}" target="_blank" rel="noopener" class="text-violet-300 hover:text-violet-200">Instagram</a>
                @endif
            </div>

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
                            <p class="text-sm text-slate-400">{{ $album->release_label }} · {{ $album->tracks_count }} tracks</p>
                        </div>
                        <span class="music-badge music-badge-{{ $album->status }}">{{ strtoupper($album->status) }}</span>
                    </div>
                    <a href="{{ route('albums.show', $album) }}" class="music-btn-secondary mt-4">Open release</a>
                </article>
            @empty
                <p class="text-slate-400">This artist has no releases yet.</p>
            @endforelse
        </div>
    </section>
@endsection
