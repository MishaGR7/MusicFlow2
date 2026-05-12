@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-3xl font-bold text-white">My Favorites</h1>

    <div class="grid gap-6 md:grid-cols-2">
        @forelse($favorites as $artist)
            <article class="music-card">
                <div class="flex items-center gap-4">
                    <img src="{{ $artist->photo ? asset('storage/' . $artist->photo) : 'https://placehold.co/200x200/020617/a78bfa?text=Artist' }}" class="h-16 w-16 rounded-full object-cover" alt="{{ $artist->name }}" />
                    <div>
                        <h2 class="text-lg font-semibold text-white">{{ $artist->name }}</h2>
                        <p class="text-sm text-slate-400">{{ $artist->country ?? 'Unknown country' }}</p>
                    </div>
                </div>

                <p class="mt-4 text-sm text-slate-300">{{ $artist->bio ?: 'No biography yet.' }}</p>

                <div class="mt-4 space-y-2 text-sm">
                    @forelse($artist->albums as $album)
                        <div class="rounded-lg border border-slate-800 bg-slate-950/80 px-3 py-2 text-slate-300">
                            {{ $album->title }} <span class="text-slate-500">({{ $album->release_label }})</span>
                        </div>
                    @empty
                        <p class="text-slate-500">No albums yet.</p>
                    @endforelse
                </div>
            </article>
        @empty
            <p class="text-slate-400">You have no favorite artists yet.</p>
        @endforelse
    </div>
@endsection
