@csrf
<div class="grid gap-4 md:grid-cols-2">
    <input class="music-input w-full" name="name" value="{{ old('name', $artist->name ?? '') }}" placeholder="Artist name" minlength="2" required />
    <input class="music-input w-full" name="country" value="{{ old('country', $artist->country ?? '') }}" placeholder="Country" />
    <textarea class="music-input w-full md:col-span-2" name="bio" rows="4" placeholder="Biography">{{ old('bio', $artist->bio ?? '') }}</textarea>
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
