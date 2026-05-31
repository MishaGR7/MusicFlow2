@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-white">Manage Artists</h1>
        <a href="{{ route('admin.artists.create') }}" class="music-btn-primary">Add artist</a>
    </div>

    <form method="GET" action="{{ route('admin.artists.index') }}" class="mb-6 grid gap-4 rounded-2xl border border-slate-800 bg-slate-900/70 p-4 md:grid-cols-4">
        <select name="company" class="music-input md:col-span-2">
            <option value="">All companies</option>
            @foreach($companies as $company)
                <option value="{{ $company }}" @selected($filters['company'] === $company)>{{ $company }}</option>
            @endforeach
        </select>
        <select name="type" class="music-input">
            <option value="">All types</option>
            @foreach($types as $type)
                <option value="{{ $type }}" @selected($filters['type'] === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        <div class="flex gap-3">
            <button class="music-btn-primary" type="submit">Filter</button>
            <a href="{{ route('admin.artists.index') }}" class="music-btn-secondary">Reset</a>
        </div>
    </form>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach($artists as $artist)
            <article class="music-card">
                <img src="{{ $artist->photo ? asset('storage/' . $artist->photo) : 'https://placehold.co/500x500/0f172a/a78bfa?text=Artist' }}" class="h-48 w-full rounded-xl object-cover" alt="{{ $artist->name }}" />
                <h2 class="mt-3 text-xl font-semibold text-white">{{ $artist->name }}</h2>
                <p class="text-sm text-slate-400">{{ $artist->country ?: 'Unknown country' }} · {{ ucfirst($artist->artist_type ?? 'group') }}</p>
                <p class="mt-2 text-sm text-slate-300">{{ $artist->company ?: 'No company' }}</p>
                <p class="text-sm text-slate-400">{{ $artist->albums_count }} releases · {{ $artist->followers_count }} followers</p>
                @if($artist->debut_date)
                    <p class="text-sm text-slate-400">Debut: {{ $artist->debut_date->format('d M Y') }}</p>
                @endif
                <p class="mt-2 line-clamp-3 text-sm text-slate-300">{{ $artist->bio ?: 'No biography yet.' }}</p>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('admin.artists.edit', $artist) }}" class="music-btn-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.artists.destroy', $artist) }}" onsubmit="return confirm('Delete this artist? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-lg border border-rose-700 px-3 py-2 text-sm text-rose-300 hover:bg-rose-900/20">Delete</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-8">{{ $artists->links() }}</div>
@endsection
