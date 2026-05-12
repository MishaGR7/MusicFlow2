<nav class="flex items-center gap-4 text-sm">
    <a class="music-nav-link" href="{{ route('home') }}">Home</a>
    <a class="music-nav-link" href="{{ route('albums.index') }}">Releases</a>
    <a class="music-nav-link" href="{{ route('artists.index') }}">Artists</a>

    @auth
        <a class="music-nav-link" href="{{ route('favorites.index') }}">My Favorites</a>
        <a class="music-nav-link" href="{{ route('profile.edit') }}">
            Profile
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="ml-1 rounded-full bg-violet-600 px-2 py-0.5 text-xs text-white">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif
        </a>

        @if(auth()->user() && auth()->user()->role === 'admin')
            <a class="music-nav-link" href="{{ route('admin.albums.index') }}">Admin Panel</a>
            <a class="music-nav-link" href="{{ route('admin.artists.index') }}">Artists</a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="music-btn-secondary" type="submit">Logout</button>
        </form>
    @else
        <a class="music-nav-link" href="{{ route('login') }}">Login</a>
        <a class="music-btn-primary" href="{{ route('register') }}">Register</a>
    @endauth
</nav>
