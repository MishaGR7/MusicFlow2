@csrf
@php
    $trackRows = old('tracks');

    if ($trackRows === null && isset($album)) {
        $trackRows = $album->tracks->map(fn ($track) => [
            'title' => $track->title,
            'duration' => $track->duration,
            'is_title_track' => $track->is_title_track,
        ])->values()->all();
    }

    if ($trackRows === null || $trackRows === []) {
        $trackRows = [
            ['title' => '', 'duration' => '', 'is_title_track' => false],
            ['title' => '', 'duration' => '', 'is_title_track' => false],
            ['title' => '', 'duration' => '', 'is_title_track' => false],
        ];
    }

    $selectedTitleTrack = old('title_track_index');

    if ($selectedTitleTrack === null) {
        foreach ($trackRows as $index => $trackRow) {
            if (! empty($trackRow['is_title_track'])) {
                $selectedTitleTrack = (string) $index;
                break;
            }
        }
    }
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <select name="artist_id" class="music-input w-full" required>
        <option value="">Select artist</option>
        @foreach($artists as $artist)
            <option value="{{ $artist->id }}" @selected(old('artist_id', $album->artist_id ?? null) == $artist->id)>{{ $artist->name }}</option>
        @endforeach
    </select>
    <input class="music-input w-full" name="title" value="{{ old('title', $album->title ?? '') }}" placeholder="Album title" minlength="2" required />
    <div class="md:col-span-2 grid gap-4 md:grid-cols-3">
        <input class="music-input w-full" type="number" name="release_year" value="{{ old('release_year', $album->release_year ?? '') }}" placeholder="Year (e.g. 2023)" min="1900" max="2030" />
        <input class="music-input w-full" type="number" name="release_month" value="{{ old('release_month', $album->release_month ?? '') }}" placeholder="Month (1-12)" min="1" max="12" />
        <input class="music-input w-full" type="number" name="release_day" value="{{ old('release_day', $album->release_day ?? '') }}" placeholder="Day (1-31)" min="1" max="31" />
    </div>
    <select name="status" class="music-input w-full" required>
        @foreach(['published', 'announced', 'soon', 'tba'] as $status)
            <option value="{{ $status }}" @selected(old('status', $album->status ?? 'tba') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <input class="music-input w-full md:col-span-2" type="url" name="spotify_url" value="{{ old('spotify_url', $album->spotify_url ?? '') }}" placeholder="Spotify album URL https://open.spotify.com/album/..." />
    <input class="music-input w-full md:col-span-2" type="file" name="cover" accept="image/*" />
</div>

<section class="mt-8 rounded-xl border border-slate-800 bg-slate-950/60 p-4">
    <div class="mb-4 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-white">Track list</h2>
            <p class="text-sm text-slate-400">Add album tracks and mark exactly one title track.</p>
        </div>
        <button class="music-btn-secondary" type="button" data-add-track>Add track</button>
    </div>

    <div class="space-y-3" data-track-list>
        @foreach($trackRows as $index => $trackRow)
            <div class="grid gap-3 rounded-lg border border-slate-800 bg-slate-900/70 p-3 md:grid-cols-[56px_minmax(0,1fr)_160px_120px] md:items-center" data-track-row>
                <p class="text-sm font-semibold text-slate-400" data-track-number>#{{ $index + 1 }}</p>
                <input class="music-input w-full" name="tracks[{{ $index }}][title]" value="{{ $trackRow['title'] ?? '' }}" placeholder="Track title" />
                <input class="music-input w-full" name="tracks[{{ $index }}][duration]" value="{{ $trackRow['duration'] ?? '' }}" placeholder="Duration 3:45" />
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input type="radio" name="title_track_index" value="{{ $index }}" @checked((string) $selectedTitleTrack === (string) $index) />
                    Title track
                </label>
            </div>
        @endforeach
    </div>
</section>

@if($errors->any())
    <ul class="mt-4 space-y-1 text-sm text-rose-400">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<button class="music-btn-primary mt-4" type="submit">Save album</button>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const list = document.querySelector('[data-track-list]');
        const addButton = document.querySelector('[data-add-track]');

        if (!list || !addButton) {
            return;
        }

        const renumberTracks = () => {
            list.querySelectorAll('[data-track-row]').forEach((row, index) => {
                row.querySelector('[data-track-number]').textContent = `#${index + 1}`;
                row.querySelectorAll('input').forEach((input) => {
                    input.name = input.name.replace(/tracks\[\d+\]/, `tracks[${index}]`);

                    if (input.type === 'radio') {
                        input.value = index;
                    }
                });
            });
        };

        addButton.addEventListener('click', () => {
            const rows = list.querySelectorAll('[data-track-row]');
            const clone = rows[rows.length - 1].cloneNode(true);

            clone.querySelectorAll('input').forEach((input) => {
                if (input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });

            list.appendChild(clone);
            renumberTracks();
        });
    });
</script>
