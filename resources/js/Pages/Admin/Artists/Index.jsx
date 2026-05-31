import { router, useForm } from '@inertiajs/react';
import { Edit, Filter, Plus, RotateCcw, Trash2 } from 'lucide-react';
import Button from '../../../Components/Button';
import EmptyState from '../../../Components/EmptyState';
import { Select } from '../../../Components/Form';
import Pagination from '../../../Components/Pagination';
import AppLayout from '../../../Layouts/AppLayout';

export default function Index({ artists, companies = [], types = [], filters = {} }) {
    const { data, setData, get } = useForm({
        company: filters.company || '',
        type: filters.type || '',
    });

    const submit = (event) => {
        event.preventDefault();
        get('/admin/artists', { preserveState: true });
    };

    const destroy = (artist) => {
        if (window.confirm('Delete this artist? This action cannot be undone.')) {
            router.delete(`/admin/artists/${artist.id}`);
        }
    };

    return (
        <AppLayout title="Manage Artists">
            <div className="mb-6 flex items-center justify-between">
                <h1 className="text-3xl font-bold text-white">Manage Artists</h1>
                <Button href="/admin/artists/create" variant="primary"><Plus size={16} /> Add artist</Button>
            </div>
            <form onSubmit={submit} className="mb-6 grid gap-4 rounded-lg border border-slate-800 bg-slate-900/70 p-4 md:grid-cols-4">
                <Select className="md:col-span-2" value={data.company} onChange={(e) => setData('company', e.target.value)}>
                    <option value="">All companies</option>
                    {companies.map((company) => <option key={company} value={company}>{company}</option>)}
                </Select>
                <Select value={data.type} onChange={(e) => setData('type', e.target.value)}>
                    <option value="">All types</option>
                    {types.map((type) => <option key={type} value={type}>{type[0].toUpperCase() + type.slice(1)}</option>)}
                </Select>
                <div className="flex gap-3">
                    <Button type="submit" variant="primary"><Filter size={16} /> Filter</Button>
                    <Button href="/admin/artists"><RotateCcw size={16} /> Reset</Button>
                </div>
            </form>
            <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                {artists.data.length ? artists.data.map((artist) => (
                    <article key={artist.id} className="music-card">
                        <img src={artist.photo_url} className="h-48 w-full rounded-lg object-cover" alt={artist.name} />
                        <h2 className="mt-3 text-xl font-semibold text-white">{artist.name}</h2>
                        <p className="text-sm text-slate-400">{artist.country || 'Unknown country'} · {artist.artist_type_label}</p>
                        <p className="mt-2 text-sm text-slate-300">{artist.company || 'No company'}</p>
                        <p className="text-sm text-slate-400">{artist.albums_count} releases · {artist.followers_count} followers</p>
                        {artist.debut_date_label && <p className="text-sm text-slate-400">Debut: {artist.debut_date_label}</p>}
                        <p className="mt-2 line-clamp-3 text-sm text-slate-300">{artist.bio || 'No biography yet.'}</p>
                        <div className="mt-4 flex gap-2">
                            <Button href={`/admin/artists/${artist.id}/edit`}><Edit size={16} /> Edit</Button>
                            <Button variant="danger" type="button" onClick={() => destroy(artist)}><Trash2 size={16} /> Delete</Button>
                        </div>
                    </article>
                )) : <EmptyState>No artists found.</EmptyState>}
            </div>
            <Pagination links={artists.links} />
        </AppLayout>
    );
}
