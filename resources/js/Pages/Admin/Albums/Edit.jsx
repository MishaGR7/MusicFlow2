import { useForm } from '@inertiajs/react';
import AppLayout from '../../../Layouts/AppLayout';
import AlbumForm from './Form';

export default function Edit({ album, artists = [] }) {
    const selectedTitleTrack = album.tracks.findIndex((track) => track.is_title_track);
    const { data, setData, post, processing, errors } = useForm({
        _method: 'PUT',
        artist_id: album.artist_id || '',
        title: album.title || '',
        release_year: album.release_year || '',
        release_month: album.release_month || '',
        release_day: album.release_day || '',
        status: album.status || 'tba',
        spotify_url: album.spotify_url || '',
        cover: null,
        tracks: album.tracks.map((track) => ({ title: track.title || '', duration: track.duration || '' })),
        title_track_index: selectedTitleTrack >= 0 ? String(selectedTitleTrack) : '',
    });

    return (
        <AppLayout title="Edit Album">
            <h1 className="mb-6 text-3xl font-bold text-white">Edit Album</h1>
            <form onSubmit={(event) => { event.preventDefault(); post(`/admin/albums/${album.id}`, { forceFormData: true }); }} encType="multipart/form-data" className="rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <AlbumForm data={data} setData={setData} errors={errors} processing={processing} artists={artists} />
            </form>
        </AppLayout>
    );
}
