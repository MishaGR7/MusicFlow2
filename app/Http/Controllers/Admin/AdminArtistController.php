<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminArtistController extends Controller
{
    public function index(Request $request): View
    {
        $company = $request->string('company')->toString();
        $type = $request->string('type')->toString();

        $artists = Artist::query()
            ->withCount(['albums', 'followers'])
            ->when($company, fn ($query) => $query->where('company', $company))
            ->when($type, fn ($query) => $query->where('artist_type', $type))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $companies = Artist::query()
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->distinct()
            ->orderBy('company')
            ->pluck('company');

        return view('admin.artists.index', [
            'artists' => $artists,
            'companies' => $companies,
            'types' => ['solo', 'group', 'band', 'duo', 'project'],
            'filters' => compact('company', 'type'),
        ]);
    }

    public function create(): View
    {
        return view('admin.artists.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateArtist($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('artists/photos', 'public');
        }

        Artist::create($validated);

        return redirect()->route('admin.artists.index')->with('status', 'Artist created.');
    }

    public function edit(Artist $artist): View
    {
        return view('admin.artists.edit', compact('artist'));
    }

    public function update(Request $request, Artist $artist): RedirectResponse
    {
        $validated = $this->validateArtist($request);

        if ($request->hasFile('photo')) {
            if ($artist->photo) {
                Storage::disk('public')->delete($artist->photo);
            }

            $validated['photo'] = $request->file('photo')->store('artists/photos', 'public');
        }

        $artist->update($validated);

        return redirect()->route('admin.artists.index')->with('status', 'Artist updated.');
    }

    public function destroy(Artist $artist): RedirectResponse
    {
        if ($artist->photo) {
            Storage::disk('public')->delete($artist->photo);
        }

        $artist->delete();

        return back()->with('status', 'Artist deleted.');
    }

    private function validateArtist(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'country' => ['nullable', 'string', 'max:100'],
            'debut_date' => ['nullable', 'date'],
            'company' => ['nullable', 'string', 'max:255'],
            'artist_type' => ['required', Rule::in(['solo', 'group', 'band', 'duo', 'project'])],
            'members_count' => ['nullable', 'integer', 'min:1', 'max:200'],
            'fandom_name' => ['nullable', 'string', 'max:255'],
            'official_site' => ['nullable', 'url', 'max:255'],
            'spotify_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'photo' => ['nullable', 'image', 'max:3072'],
        ]);
    }
}
