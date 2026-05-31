import { useForm } from '@inertiajs/react';
import AppLayout from '../../../Layouts/AppLayout';
import AlbumForm from './Form';

export default function Create({ artists = [] }) {
    const { data, setData, post, processing, errors } = useForm({
        artist_id: '',
        title: '',
        release_year: '',
        release_month: '',
        release_day: '',
        status: 'tba',
        spotify_url: '',
        cover: null,
        tracks: [],
        title_track_index: '',
    });

    return (
        <AppLayout title="Create Album">
            <h1 className="mb-6 text-3xl font-bold text-white">Create Album</h1>
            <form onSubmit={(event) => { event.preventDefault(); post('/admin/albums', { forceFormData: true }); }} encType="multipart/form-data" className="rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <AlbumForm data={data} setData={setData} errors={errors} processing={processing} artists={artists} />
            </form>
        </AppLayout>
    );
}
