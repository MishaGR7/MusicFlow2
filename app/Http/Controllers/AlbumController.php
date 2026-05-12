<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $country = $request->string('country')->toString();
        $search = $request->string('q')->toString();
        $onlyFavorites = $request->boolean('favorites');

        $albums = Album::query()
            ->with(['artist', 'artist.followers'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($country, fn ($query) => $query->whereHas('artist', fn ($artistQuery) => $artistQuery->where('country', $country)))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhereHas('artist', fn ($artistQuery) => $artistQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($onlyFavorites && $request->user(), function ($query) use ($request) {
                $favoriteArtistIds = $request->user()->favoriteArtists()->pluck('artists.id');
                $query->whereIn('artist_id', $favoriteArtistIds);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $favoriteArtistIds = $request->user()
            ? $request->user()->favoriteArtists()->pluck('artists.id')->all()
            : [];

        $countries = Album::query()
            ->join('artists', 'artists.id', '=', 'albums.artist_id')
            ->whereNotNull('artists.country')
            ->distinct()
            ->orderBy('artists.country')
            ->pluck('artists.country');

        return view('albums.index', [
            'albums' => $albums,
            'favoriteArtistIds' => $favoriteArtistIds,
            'filters' => compact('status', 'country', 'search', 'onlyFavorites'),
            'countries' => $countries,
            'statuses' => ['published', 'announced', 'soon', 'tba'],
        ]);
    }

    public function show(Album $album): View
    {
        $album->load('artist');

        return view('albums.show', compact('album'));
    }
}
