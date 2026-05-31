import { useForm } from '@inertiajs/react';
import ArtistForm from './Form';
import AppLayout from '../../../Layouts/AppLayout';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        country: '',
        debut_date: '',
        company: '',
        artist_type: 'group',
        members_count: '',
        fandom_name: '',
        official_site: '',
        spotify_url: '',
        instagram_url: '',
        bio: '',
        photo: null,
    });

    return (
        <AppLayout title="Create Artist">
            <h1 className="mb-6 text-3xl font-bold text-white">Create Artist</h1>
            <form onSubmit={(event) => { event.preventDefault(); post('/admin/artists', { forceFormData: true }); }} encType="multipart/form-data" className="rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <ArtistForm data={data} setData={setData} errors={errors} processing={processing} />
            </form>
        </AppLayout>
    );
}
