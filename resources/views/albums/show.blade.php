@extends('layouts.app')

@section('content')
    <article class="grid gap-8 lg:grid-cols-[320px_minmax(0,1fr)]">
        <div class="music-card">
            <img src="{{ $album->cover ? asset('storage/' . $album->cover) : 'https://placehold.co/800x800/0f172a/a78bfa?text=Album' }}" alt="{{ $album->title }}" class="h-full w-full rounded-xl object-cover" />
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
            <p class="text-sm uppercase tracking-[0.3em] text-violet-300">{{ strtoupper($album->status) }}</p>
            <h1 class="mt-3 text-4xl font-bold text-white">{{ $album->title }}</h1>
            <p class="mt-3 text-lg text-slate-300">
                by <a href="{{ route('artists.show', $album->artist) }}" class="text-violet-300 hover:text-violet-200">{{ $album->artist->name }}</a>
            </p>
            <p class="mt-2 text-sm text-slate-400">Release date: {{ $album->release_label }}</p>
            <p class="mt-2 text-sm text-slate-400">Country: {{ $album->artist->country ?? 'Unknown' }}</p>

            @auth
                <form method="POST" action="{{ route('favorites.toggle', $album->artist) }}" class="mt-6">
                    @csrf
                    <button type="submit" class="music-btn-primary">Toggle artist in favorites</button>
                </form>
            @endif
        </div>
    </article>
@endsection
