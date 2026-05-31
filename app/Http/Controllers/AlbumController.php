<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AlbumController extends Controller
{
    public function index(Request $request): Response
    {
        $status = $request->string('status')->toString();
        $country = $request->string('country')->toString();
        $search = $request->string('q')->toString();
        $onlyFavorites = $request->boolean('favorites');

        $albums = Album::query()
            ->with(['artist', 'artist.followers', 'titleTrack'])
            ->withCount('tracks')
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

        return Inertia::render('Albums/Index', [
            'albums' => $this->paginationData($albums, fn (Album $album) => $this->albumData($album)),
            'favoriteArtistIds' => $favoriteArtistIds,
            'filters' => compact('status', 'country', 'search', 'onlyFavorites'),
            'countries' => $countries,
            'statuses' => ['published', 'announced', 'tba'],
            'canFilterFavorites' => $request->user() !== null,
        ]);
    }

    public function show(Album $album): Response
    {
        $album->load(['artist', 'tracks', 'titleTrack']);

        return Inertia::render('Albums/Show', [
            'album' => $this->albumData($album, true),
        ]);
    }
}
