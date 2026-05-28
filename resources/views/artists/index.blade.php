@extends('layouts.app')

@section('content')
    <section class="mb-8 rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
        <h1 class="text-3xl font-bold text-white">Artists</h1>
        <p class="mt-2 text-sm text-slate-400">Public pages for every artist, with release history and follower actions.</p>

        <form method="GET" action="{{ route('artists.index') }}" class="mt-6 grid gap-4 md:grid-cols-4">
            <input name="q" value="{{ $filters['search'] }}" placeholder="Search artists, company, fandom" class="music-input md:col-span-2" />
            <select name="country" class="music-input">
                <option value="">All countries</option>
                @foreach($countries as $value)
                    <option value="{{ $value }}" @selected($filters['country'] === $value)>{{ $value }}</option>
                @endforeach
            </select>
            <select name="company" class="music-input">
                <option value="">All companies</option>
                @foreach($companies as $value)
                    <option value="{{ $value }}" @selected($filters['company'] === $value)>{{ $value }}</option>
                @endforeach
            </select>
            <select name="type" class="music-input">
                <option value="">All types</option>
                @foreach($types as $value)
                    <option value="{{ $value }}" @selected($filters['type'] === $value)>{{ ucfirst($value) }}</option>
                @endforeach
            </select>
            <div class="flex flex-wrap gap-3 md:col-span-4">
                <button class="music-btn-primary" type="submit">Apply Filters</button>
                <a href="{{ route('artists.index') }}" class="music-btn-secondary">Reset</a>
            </div>
        </form>
    </section>

    <section class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($artists as $artist)
            <article class="music-card">
                <img src="{{ $artist->photo ? asset('storage/' . $artist->photo) : 'https://placehold.co/800x800/020617/a78bfa?text=Artist' }}" alt="{{ $artist->name }}" class="h-56 w-full rounded-xl object-cover" />
                <h2 class="mt-4 text-xl font-semibold text-white">{{ $artist->name }}</h2>
                <p class="mt-1 text-sm text-slate-400">{{ $artist->country ?: 'Unknown country' }} · {{ ucfirst($artist->artist_type ?? 'group') }}</p>
                <p class="mt-2 text-sm text-slate-300">{{ $artist->company ?: 'Independent / unknown company' }}</p>
                @if($artist->debut_date)
                    <p class="mt-1 text-sm text-slate-400">Debut: {{ $artist->debut_date->format('d M Y') }}</p>
                @endif
                <p class="mt-3 text-sm text-slate-300">{{ $artist->albums_count }} releases · {{ $artist->followers_count }} followers</p>
                <p class="mt-3 line-clamp-3 text-sm text-slate-400">{{ $artist->bio ?: 'No biography yet.' }}</p>

                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('artists.show', $artist) }}" class="music-btn-secondary">View profile</a>
                    @auth
                        <form method="POST" action="{{ route('favorites.toggle', $artist) }}">
                            @csrf
                            <button type="submit" class="music-btn-secondary">Toggle favorite</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <p class="text-slate-400">No artists found.</p>
        @endforelse
    </section>

    <div class="mt-8">{{ $artists->links() }}</div>
@endsection
