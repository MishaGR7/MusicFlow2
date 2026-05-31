<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ArtistController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('q')->toString();
        $country = $request->string('country')->toString();
        $company = $request->string('company')->toString();
        $type = $request->string('type')->toString();

        $artists = Artist::query()
            ->withCount(['albums', 'followers'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('fandom_name', 'like', "%{$search}%");
                });
            })
            ->when($country, fn ($query) => $query->where('country', $country))
            ->when($company, fn ($query) => $query->where('company', $company))
            ->when($type, fn ($query) => $query->where('artist_type', $type))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $countries = Artist::query()
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $companies = Artist::query()
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->distinct()
            ->orderBy('company')
            ->pluck('company');

        return Inertia::render('Artists/Index', [
            'artists' => $this->paginationData($artists, fn (Artist $artist) => $this->artistData($artist)),
            'countries' => $countries,
            'companies' => $companies,
            'types' => ['solo', 'group', 'band', 'duo', 'project'],
            'filters' => compact('search', 'country', 'company', 'type'),
        ]);
    }

    public function show(Artist $artist): Response
    {
        $artist->load([
            'albums' => fn ($query) => $query->withCount('tracks')->latest(),
            'followers',
        ]);

        return Inertia::render('Artists/Show', [
            'artist' => $this->artistData($artist, true),
        ]);
    }
}
