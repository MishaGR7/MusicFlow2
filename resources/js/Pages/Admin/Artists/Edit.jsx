import { useForm } from '@inertiajs/react';
import ArtistForm from './Form';
import AppLayout from '../../../Layouts/AppLayout';

export default function Edit({ artist }) {
    const { data, setData, post, processing, errors } = useForm({
        _method: 'PUT',
        name: artist.name || '',
        country: artist.country || '',
        debut_date: artist.debut_date || '',
        company: artist.company || '',
        artist_type: artist.artist_type || 'group',
        members_count: artist.members_count || '',
        fandom_name: artist.fandom_name || '',
        official_site: artist.official_site || '',
        spotify_url: artist.spotify_url || '',
        instagram_url: artist.instagram_url || '',
        bio: artist.bio || '',
        photo: null,
    });

    return (
        <AppLayout title="Edit Artist">
            <h1 className="mb-6 text-3xl font-bold text-white">Edit Artist</h1>
            <form onSubmit={(event) => { event.preventDefault(); post(`/admin/artists/${artist.id}`, { forceFormData: true }); }} encType="multipart/form-data" className="rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <ArtistForm data={data} setData={setData} errors={errors} processing={processing} />
            </form>
        </AppLayout>
    );
}
