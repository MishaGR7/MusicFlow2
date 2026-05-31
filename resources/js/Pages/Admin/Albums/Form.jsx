import { Plus, Save, X } from 'lucide-react';
import Button from '../../../Components/Button';
import { FieldError, Input, Select } from '../../../Components/Form';

const emptyTrack = { title: '', duration: '' };

export default function AlbumForm({ data, setData, errors, processing, artists = [], submitLabel = 'Save album' }) {
    const setTrackCount = (count) => {
        const normalizedCount = Math.max(0, Number.isNaN(count) ? 0 : count);
        const nextTracks = Array.from({ length: normalizedCount }, (_, index) => data.tracks[index] ?? { ...emptyTrack });
        const selectedTitleTrack = data.title_track_index === '' ? '' : Number(data.title_track_index);

        setData({
            ...data,
            tracks: nextTracks,
            title_track_index: selectedTitleTrack !== '' && selectedTitleTrack >= normalizedCount ? '' : data.title_track_index,
        });
    };

    const setTrack = (index, field, value) => {
        const tracks = [...data.tracks];
        tracks[index] = { ...tracks[index], [field]: value };
        setData('tracks', tracks);
    };

    const addTrack = () => {
        setData('tracks', [...data.tracks, { ...emptyTrack }]);
    };

    const removeTrack = (index) => {
        const tracks = data.tracks.filter((_, trackIndex) => trackIndex !== index);
        const selectedTitleTrack = data.title_track_index === '' ? '' : Number(data.title_track_index);
        const nextTitleTrack = selectedTitleTrack === ''
            ? ''
            : selectedTitleTrack === index
                ? ''
                : selectedTitleTrack > index
                    ? String(selectedTitleTrack - 1)
                    : String(selectedTitleTrack);

        setData({
            ...data,
            tracks,
            title_track_index: nextTitleTrack,
        });
    };

    return (
        <div>
            <div className="grid gap-4 md:grid-cols-2">
                <Select value={data.artist_id} onChange={(e) => setData('artist_id', e.target.value)} required error={errors.artist_id}>
                    <option value="">Select artist</option>
                    {artists.map((artist) => <option key={artist.id} value={artist.id}>{artist.name}</option>)}
                </Select>
                <Input value={data.title} onChange={(e) => setData('title', e.target.value)} placeholder="Album title" minLength="2" error={errors.title} />
                <div className="grid gap-4 md:col-span-2 md:grid-cols-3">
                    <Input type="number" value={data.release_year} onChange={(e) => setData('release_year', e.target.value)} placeholder="Year (e.g. 2026)" min="1900" max="2100" error={errors.release_year} />
                    <Input type="number" value={data.release_month} onChange={(e) => setData('release_month', e.target.value)} placeholder="Month (1-12)" min="1" max="12" error={errors.release_month} />
                    <Input type="number" value={data.release_day} onChange={(e) => setData('release_day', e.target.value)} placeholder="Day (1-31)" min="1" max="31" error={errors.release_day} />
                </div>
                <Select value={data.status} onChange={(e) => setData('status', e.target.value)} error={errors.status}>
                    {['published', 'announced', 'tba'].map((status) => <option key={status} value={status}>{status[0].toUpperCase() + status.slice(1)}</option>)}
                </Select>
                <Input className="md:col-span-2" type="url" value={data.spotify_url} onChange={(e) => setData('spotify_url', e.target.value)} placeholder="Spotify album URL https://open.spotify.com/album/..." error={errors.spotify_url} />
                <Input className="md:col-span-2" type="file" accept="image/*" onChange={(e) => setData('cover', e.target.files[0])} error={errors.cover} />
            </div>

            <section className="mt-8 rounded-lg border border-slate-800 bg-slate-950/60 p-4">
                <div className="mb-4 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 className="text-lg font-semibold text-white">Track list</h2>
                        <p className="text-sm text-slate-400">Set the number of tracks, then fill titles and optionally mark the title track.</p>
                    </div>
                    <div className="flex flex-wrap items-end gap-3">
                        <Input
                            className="w-32"
                            type="number"
                            min="0"
                            max="200"
                            value={data.tracks.length}
                            onChange={(event) => setTrackCount(Number.parseInt(event.target.value, 10))}
                            aria-label="Track count"
                        />
                        <Button type="button" onClick={addTrack}><Plus size={16} /> Add track</Button>
                    </div>
                </div>
                {data.tracks.length === 0 ? (
                    <div className="rounded-lg border border-dashed border-slate-700 bg-slate-900/50 p-4 text-sm text-slate-400">
                        No track rows yet.
                    </div>
                ) : (
                    <div className="space-y-3">
                        {data.tracks.map((track, index) => (
                        <div key={index} className="grid gap-3 rounded-lg border border-slate-800 bg-slate-900/70 p-3 md:grid-cols-[56px_minmax(0,1fr)_160px_150px_44px] md:items-center">
                            <p className="text-sm font-semibold text-slate-400">#{index + 1}</p>
                            <Input value={track.title} onChange={(e) => setTrack(index, 'title', e.target.value)} placeholder="Track title" error={errors[`tracks.${index}.title`]} />
                            <Input value={track.duration || ''} onChange={(e) => setTrack(index, 'duration', e.target.value)} placeholder="Duration 3:45" error={errors[`tracks.${index}.duration`]} />
                            <label className="flex items-center gap-2 text-sm text-slate-300">
                                <input type="radio" name="title_track_index" value={index} checked={String(data.title_track_index) === String(index)} onChange={(e) => setData('title_track_index', e.target.value)} className="accent-violet-500" />
                                Title track
                            </label>
                            <button type="button" onClick={() => removeTrack(index)} className="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-700 text-slate-400 hover:border-rose-700 hover:text-rose-300" aria-label="Remove track">
                                <X size={16} />
                            </button>
                        </div>
                        ))}
                    </div>
                )}
                <FieldError message={errors.title_track_index} />
            </section>

            <Button className="mt-4" type="submit" variant="primary" disabled={processing}>
                <Save size={16} /> {submitLabel}
            </Button>
        </div>
    );
}
