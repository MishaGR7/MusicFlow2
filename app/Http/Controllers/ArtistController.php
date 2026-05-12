<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();
        $country = $request->string('country')->toString();

        $artists = Artist::query()
            ->withCount(['albums', 'followers'])
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($country, fn ($query) => $query->where('country', $country))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $countries = Artist::query()
            ->whereNotNull('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        return view('artists.index', [
            'artists' => $artists,
            'countries' => $countries,
            'filters' => compact('search', 'country'),
        ]);
    }

    public function show(Artist $artist): View
    {
        $artist->load([
            'albums' => fn ($query) => $query->latest(),
            'followers',
        ]);

        return view('artists.show', compact('artist'));
    }
}
