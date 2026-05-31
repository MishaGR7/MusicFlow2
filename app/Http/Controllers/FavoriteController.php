<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Artist $artist): RedirectResponse
    {
        $changes = $request->user()->favoriteArtists()->toggle($artist->id);

        $message = count($changes['attached']) > 0
            ? 'Artist added to favorites.'
            : 'Artist removed from favorites.';

        return back()->with('status', $message);
    }

    public function index(Request $request): Response
    {
        $favorites = $request->user()
            ->favoriteArtists()
            ->with(['albums' => fn ($query) => $query->latest()])
            ->latest('artist_user.created_at')
            ->get();

        return Inertia::render('Favorites/Index', [
            'favorites' => $favorites->map(fn (Artist $artist) => $this->artistData($artist, true)),
        ]);
    }
}
