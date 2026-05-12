<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminArtistController extends Controller
{
    public function index(): View
    {
        $artists = Artist::latest()->paginate(12);

        return view('admin.artists.index', compact('artists'));
    }

    public function create(): View
    {
        return view('admin.artists.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'country' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'max:3072'],
        ]);

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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'country' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'max:3072'],
        ]);

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
}
