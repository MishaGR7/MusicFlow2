@csrf
<div class="grid gap-4 md:grid-cols-2">
    <input class="music-input w-full" name="name" value="{{ old('name', $artist->name ?? '') }}" placeholder="Artist name" minlength="2" required />
    <input class="music-input w-full" name="country" value="{{ old('country', $artist->country ?? '') }}" placeholder="Country" />
    <input class="music-input w-full" type="date" name="debut_date" value="{{ old('debut_date', isset($artist) && $artist->debut_date ? $artist->debut_date->format('Y-m-d') : '') }}" />
    <input class="music-input w-full" name="company" value="{{ old('company', $artist->company ?? '') }}" placeholder="Company / agency" />
    <select class="music-input w-full" name="artist_type" required>
        @foreach(['solo' => 'Solo', 'group' => 'Group', 'band' => 'Band', 'duo' => 'Duo', 'project' => 'Project'] as $value => $label)
            <option value="{{ $value }}" @selected(old('artist_type', $artist->artist_type ?? 'group') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <input class="music-input w-full" type="number" name="members_count" value="{{ old('members_count', $artist->members_count ?? '') }}" placeholder="Members count" min="1" max="200" />
    <input class="music-input w-full" name="fandom_name" value="{{ old('fandom_name', $artist->fandom_name ?? '') }}" placeholder="Fandom name" />
    <input class="music-input w-full" type="url" name="official_site" value="{{ old('official_site', $artist->official_site ?? '') }}" placeholder="Official site https://..." />
    <textarea class="music-input w-full md:col-span-2" name="bio" rows="8" placeholder="Detailed biography, history, achievements, concept, notes">{{ old('bio', $artist->bio ?? '') }}</textarea>
    <input class="music-input w-full md:col-span-2" type="file" name="photo" accept="image/*" />
</div>

@if($errors->any())
    <ul class="mt-4 space-y-1 text-sm text-rose-400">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<button class="music-btn-primary mt-4" type="submit">Save artist</button>
