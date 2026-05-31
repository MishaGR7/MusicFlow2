<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'MusicFlow' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="music-body">
    <div class="min-h-screen bg-slate-950/95 text-slate-100">
        <header class="border-b border-slate-800 bg-slate-950/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-4 sm:flex-nowrap sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="text-2xl font-semibold tracking-wide text-violet-400">MusicFlow</a>
                @include('layouts.navigation')
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-6 rounded-lg border border-emerald-700 bg-emerald-900/40 px-4 py-3 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            @auth
                @if(auth()->user()->unreadNotifications->isNotEmpty())
                    <div class="mb-6 rounded-lg border border-violet-800 bg-violet-900/30 px-4 py-3 text-sm">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <p class="font-semibold text-violet-100">New updates from your favorite artists</p>
                            <a href="{{ route('profile.edit') }}" class="text-violet-200 underline underline-offset-4">Open profile</a>
                        </div>
                        @foreach(auth()->user()->unreadNotifications as $notification)
                            <p>
                                {{ $notification->data['message'] ?? 'New album update.' }}
                                @if(! empty($notification->data['action_url']))
                                    <a href="{{ $notification->data['action_url'] }}" class="ml-2 text-violet-200 underline underline-offset-4">
                                        {{ $notification->data['action_label'] ?? 'Open' }}
                                    </a>
                                @endif
                            </p>
                        @endforeach
                    </div>
                @endif
            @endauth

            @yield('content')
        </main>
    </div>
</body>
</html>
