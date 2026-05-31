@extends('layouts.app')

@section('content')
    <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_380px]">
        <section class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
            <h1 class="text-3xl font-bold text-white">Profile & Settings</h1>
            <p class="mt-2 text-sm text-slate-400">Manage your identity, review favorite artists, and keep notifications tidy.</p>

            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 grid gap-4 md:grid-cols-2">
                @csrf
                @method('PATCH')
                <input name="name" value="{{ old('name', $user->name) }}" class="music-input w-full" placeholder="Name" minlength="2" required />
                <input name="email" value="{{ old('email', $user->email) }}" type="email" class="music-input w-full" placeholder="Email" required />
                <div class="md:col-span-2">
                    @if($errors->any())
                        <ul class="space-y-1 text-sm text-rose-400">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <button class="music-btn-primary" type="submit">Save profile</button>
                </div>
            </form>

            <div class="mt-10">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-white">Favorite Artists</h2>
                    <p class="text-sm text-slate-400">Quick unsubscribe controls live here.</p>
                </div>

                <div class="grid gap-4">
                    @forelse($user->favoriteArtists as $artist)
                        <article class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-white">{{ $artist->name }}</h3>
                                    <p class="text-sm text-slate-400">{{ $artist->country ?: 'Unknown country' }}</p>
                                    <p class="mt-2 text-sm text-slate-300">{{ $artist->albums->count() }} releases available</p>
                                </div>
                                <form method="POST" action="{{ route('profile.favorites.destroy', $artist) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="music-btn-secondary" type="submit">Remove</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <p class="text-slate-400">You are not following any artists yet.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <aside class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-white">Notifications</h2>
                    <p class="text-sm text-slate-400">Announcements from your favorite artists.</p>
                </div>
                @if($user->unreadNotifications->isNotEmpty())
                    <form method="POST" action="{{ route('profile.notifications.read') }}">
                        @csrf
                        <button class="music-btn-secondary" type="submit">Mark all read</button>
                    </form>
                @endif
            </div>

            <div class="space-y-3">
                @forelse($user->notifications as $notification)
                    <article class="rounded-xl border px-4 py-3 text-sm {{ $notification->read_at ? 'border-slate-800 bg-slate-950/70 text-slate-400' : 'border-violet-800 bg-violet-950/40 text-violet-100' }}">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-medium">{{ $notification->data['message'] ?? 'New release update.' }}</p>
                                <p class="mt-2 text-xs {{ $notification->read_at ? 'text-slate-500' : 'text-violet-300' }}">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if(! empty($notification->data['action_url']))
                                <a href="{{ $notification->data['action_url'] }}" class="text-xs font-semibold text-violet-300 hover:text-violet-200">
                                    {{ $notification->data['action_label'] ?? 'Open' }}
                                </a>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="text-slate-400">No notifications yet.</p>
                @endforelse
            </div>
        </aside>
    </div>
@endsection
