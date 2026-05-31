<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Artist;
use App\Notifications\NewAlbumAdded;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AdminAlbumController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('q')->toString();
        $status = $request->string('status')->toString();
        $artistId = $request->integer('artist_id') ?: null;

        $albums = Album::query()
            ->with(['artist', 'titleTrack'])
            ->withCount('tracks')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhereHas('artist', fn ($artistQuery) => $artistQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($artistId, fn ($query) => $query->where('artist_id', $artistId))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $artists = Artist::orderBy('name')->get();

        return Inertia::render('Admin/Albums/Index', [
            'albums' => $this->paginationData($albums, fn (Album $album) => $this->albumData($album)),
            'artists' => $artists->map(fn (Artist $artist) => [
                'id' => $artist->id,
                'name' => $artist->name,
            ]),
            'statuses' => ['published', 'announced', 'soon', 'tba'],
            'filters' => compact('search', 'status', 'artistId'),
        ]);
    }

    public function create(): Response
    {
        $artists = Artist::orderBy('name')->get();

        return Inertia::render('Admin/Albums/Create', [
            'artists' => $artists->map(fn (Artist $artist) => [
                'id' => $artist->id,
                'name' => $artist->name,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAlbum($request);
        $tracks = $this->validatedTracks($request);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('albums/covers', 'public');
        }

        $album = DB::transaction(function () use ($validated, $tracks) {
            $album = Album::create($validated);
            $this->syncTracks($album, $tracks);

            return $album;
        });

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
    public function edit(Album $album): Response
    {
        $album->load(['artist', 'tracks', 'titleTrack']);
        $artists = Artist::orderBy('name')->get();

        return Inertia::render('Admin/Albums/Edit', [
            'album' => $this->albumData($album, true),
            'artists' => $artists->map(fn (Artist $artist) => [
                'id' => $artist->id,
                'name' => $artist->name,
            ]),
        ]);
    }

    public function update(Request $request, Album $album): RedirectResponse
    {
        $validated = $this->validateAlbum($request);
        $tracks = $this->validatedTracks($request);

        if ($request->hasFile('cover')) {
            if ($album->cover) {
                Storage::disk('public')->delete($album->cover);
            }

            $validated['cover'] = $request->file('cover')->store('albums/covers', 'public');
        }

        DB::transaction(function () use ($album, $validated, $tracks) {
            $album->update($validated);
            $this->syncTracks($album, $tracks);
        });

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
            'spotify_url' => ['nullable', 'url', 'max:255'],
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

    private function validatedTracks(Request $request): array
    {
        $validated = $request->validate([
            'tracks' => ['nullable', 'array'],
            'tracks.*.title' => ['nullable', 'string', 'max:255'],
            'tracks.*.duration' => ['nullable', 'string', 'max:12', 'regex:/^([0-9]{1,2}:)?[0-9]{1,2}:[0-5][0-9]$/'],
            'title_track_index' => ['nullable', 'integer'],
        ], [
            'tracks.*.duration.regex' => 'Track duration must look like 3:45 or 1:02:30.',
        ]);

        $rawTracks = $validated['tracks'] ?? [];
        $titleTrackIndex = isset($validated['title_track_index']) ? (int) $validated['title_track_index'] : null;
        $tracks = [];
        $position = 1;
        $hasSelectedTitleTrack = false;

        foreach ($rawTracks as $index => $track) {
            $title = trim((string) ($track['title'] ?? ''));

            if ($title === '') {
                continue;
            }

            $isTitleTrack = $titleTrackIndex !== null && (int) $index === $titleTrackIndex;
            $hasSelectedTitleTrack = $hasSelectedTitleTrack || $isTitleTrack;

            $tracks[] = [
                'position' => $position++,
                'title' => $title,
                'duration' => trim((string) ($track['duration'] ?? '')) ?: null,
                'is_title_track' => $isTitleTrack,
            ];
        }

        if ($tracks !== [] && ! $hasSelectedTitleTrack) {
            throw ValidationException::withMessages([
                'title_track_index' => 'Select the title track for this album.',
            ]);
        }

        return $tracks;
    }

    private function syncTracks(Album $album, array $tracks): void
    {
        $album->tracks()->delete();

        foreach ($tracks as $track) {
            $album->tracks()->create($track);
        }
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
