<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

    public function index(Request $request): View
    {
        $favorites = $request->user()
            ->favoriteArtists()
            ->with(['albums' => fn ($query) => $query->latest()])
            ->latest('artist_user.created_at')
            ->get();

        return view('favorites.index', compact('favorites'));
    }
}
