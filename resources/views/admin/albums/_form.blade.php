@csrf
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
    <input class="music-input w-full md:col-span-2" type="file" name="cover" accept="image/*" />
</div>

@if($errors->any())
    <ul class="mt-4 space-y-1 text-sm text-rose-400">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<button class="music-btn-primary mt-4" type="submit">Save album</button>
