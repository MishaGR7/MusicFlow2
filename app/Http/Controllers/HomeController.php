<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Home', [
            'latestAlbums' => Album::with(['artist', 'titleTrack'])
                ->withCount('tracks')
                ->orderByDesc('release_date')
                ->latest()
                ->take(12)
                ->get()
                ->map(fn (Album $album) => $this->albumData($album)),
            'featuredArtists' => Artist::withCount(['albums', 'followers'])
                ->withMax('albums', 'release_date')
                ->orderByDesc('albums_max_release_date')
                ->orderBy('name')
                ->take(12)
                ->get()
                ->map(fn (Artist $artist) => $this->artistData($artist)),
            'stats' => [
                'albums' => Album::count(),
                'artists' => Artist::count(),
                'published' => Album::where('status', 'published')->count(),
            ],
        ]);
    }
}
