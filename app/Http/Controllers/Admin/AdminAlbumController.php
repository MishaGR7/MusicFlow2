<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Artist;
use App\Notifications\NewAlbumAdded;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminAlbumController extends Controller
{
    public function index(): View
    {
        $albums = Album::with('artist')->latest()->paginate(12);

        return view('admin.albums.index', compact('albums'));
    }

    public function create(): View
    {
        $artists = Artist::orderBy('name')->get();

        return view('admin.albums.create', compact('artists'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAlbum($request);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('albums/covers', 'public');
        }

        $album = Album::create($validated);

        $recipients = $album->artist
            ->followers()
            ->get();

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new NewAlbumAdded($album));
        }

        return redirect()->route('admin.albums.index')->with('status', 'Album created.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Album $album): View
    {
        $artists = Artist::orderBy('name')->get();

        return view('admin.albums.edit', compact('album', 'artists'));
    }

    public function update(Request $request, Album $album): RedirectResponse
    {
        $validated = $this->validateAlbum($request);

        if ($request->hasFile('cover')) {
            if ($album->cover) {
                Storage::disk('public')->delete($album->cover);
            }

            $validated['cover'] = $request->file('cover')->store('albums/covers', 'public');
        }

        $album->update($validated);

        return redirect()->route('admin.albums.index')->with('status', 'Album updated.');
    }

    public function destroy(Album $album): RedirectResponse
    {
        if ($album->cover) {
            Storage::disk('public')->delete($album->cover);
        }

        $album->delete();

        return back()->with('status', 'Album deleted.');
    }

    private function validateAlbum(Request $request): array
    {
        $validated = $request->validate([
            'artist_id' => ['required', 'exists:artists,id'],
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'status' => ['required', Rule::in(['published', 'announced', 'soon', 'tba'])],
            'release_year' => [
                Rule::requiredIf(in_array($request->input('status'), ['published', 'announced'], true)),
                'nullable',
                'integer',
                'min:1900',
                'max:2100',
            ],
            'release_month' => [
                'nullable',
                'integer',
                'min:1',
                'max:12',
            ],
            'release_day' => [
                'nullable',
                'integer',
                'min:1',
                'max:31',
            ],
            'cover' => ['nullable', 'image', 'max:3072'],
        ]);

        $year = isset($validated['release_year']) ? (int) $validated['release_year'] : null;
        $month = isset($validated['release_month']) ? (int) $validated['release_month'] : null;
        $day = isset($validated['release_day']) ? (int) $validated['release_day'] : null;

        $errors = [];

        if ($month && ! $year) {
            $errors['release_year'] = 'Add a year before specifying the month.';
        }

        if ($day && (! $year || ! $month)) {
            $errors['release_day'] = 'Add year and month before specifying the day.';
        }

        if ($year && $month && $day && ! checkdate($month, $day, $year)) {
            $errors['release_day'] = 'Enter a valid calendar date.';
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        $validated['release_date'] = $this->composeReleaseDate($year, $month, $day);

        unset($validated['release_year'], $validated['release_month'], $validated['release_day']);

        return $validated;
    }

    private function composeReleaseDate(?int $year, ?int $month, ?int $day): ?string
    {
        if (! $year) {
            return null;
        }

        $releaseDate = (string) $year;

        if ($month) {
            $releaseDate .= '-'.str_pad((string) $month, 2, '0', STR_PAD_LEFT);
        }

        if ($month && $day) {
            $releaseDate .= '-'.str_pad((string) $day, 2, '0', STR_PAD_LEFT);
        }

        return $releaseDate;
    }
}
