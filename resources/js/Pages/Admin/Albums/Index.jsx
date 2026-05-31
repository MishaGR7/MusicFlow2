import { router, useForm } from '@inertiajs/react';
import { Edit, Filter, Plus, RotateCcw, Trash2 } from 'lucide-react';
import AlbumCover from '../../../Components/AlbumCover';
import Badge from '../../../Components/Badge';
import Button from '../../../Components/Button';
import EmptyState from '../../../Components/EmptyState';
import { Input, Select } from '../../../Components/Form';
import Pagination from '../../../Components/Pagination';
import AppLayout from '../../../Layouts/AppLayout';

export default function Index({ albums, artists = [], statuses = [], filters = {} }) {
    const { data, setData, get } = useForm({
        q: filters.search || '',
        status: filters.status || '',
        artist_id: filters.artistId || '',
    });

    const submit = (event) => {
        event.preventDefault();
        get('/admin/albums', { preserveState: true });
    };

    const destroy = (album) => {
        if (window.confirm('Delete this album? This action cannot be undone.')) {
            router.delete(`/admin/albums/${album.id}`);
        }
    };

    return (
        <AppLayout title="Manage Albums">
            <div className="mb-6 flex items-center justify-between">
                <h1 className="text-3xl font-bold text-white">Manage Albums</h1>
                <Button href="/admin/albums/create" variant="primary"><Plus size={16} /> Add album</Button>
            </div>
            <form onSubmit={submit} className="mb-6 grid gap-4 rounded-lg border border-slate-800 bg-slate-900/70 p-4 md:grid-cols-4">
                <Input className="md:col-span-2" value={data.q} onChange={(e) => setData('q', e.target.value)} placeholder="Search by album or artist" />
                <Select value={data.status} onChange={(e) => setData('status', e.target.value)}>
                    <option value="">All statuses</option>
                    {statuses.map((value) => <option key={value} value={value}>{value[0].toUpperCase() + value.slice(1)}</option>)}
                </Select>
                <Select value={data.artist_id} onChange={(e) => setData('artist_id', e.target.value)}>
                    <option value="">All artists</option>
                    {artists.map((artist) => <option key={artist.id} value={artist.id}>{artist.name}</option>)}
                </Select>
                <div className="flex flex-wrap gap-3 md:col-span-4">
                    <Button type="submit" variant="primary"><Filter size={16} /> Filter</Button>
                    <Button href="/admin/albums"><RotateCcw size={16} /> Reset</Button>
                </div>
            </form>
            <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                {albums.data.length ? albums.data.map((album) => (
                    <article key={album.id} className="music-card">
                        <AlbumCover album={album} className="h-48 w-full rounded-lg object-cover" />
                        <div className="mt-3 flex items-start justify-between gap-3">
                            <div>
                                <h2 className="text-xl font-semibold text-white">{album.title || 'Untitled Album'}</h2>
                                <p className="text-sm text-slate-400">{album.artist.name}</p>
                            </div>
                            <Badge status={album.status} />
                        </div>
                        <p className="mt-2 text-sm text-slate-300">{album.tracks_count} tracks</p>
                        <p className="text-sm text-slate-400">Title track: {album.title_track?.title || 'Not selected'}</p>
                        <div className="mt-4 flex gap-2">
                            <Button href={`/admin/albums/${album.id}/edit`}><Edit size={16} /> Edit</Button>
                            <Button variant="danger" type="button" onClick={() => destroy(album)}><Trash2 size={16} /> Delete</Button>
                        </div>
                    </article>
                )) : <EmptyState>No albums found for this filter set.</EmptyState>}
            </div>
            <Pagination links={albums.links} />
        </AppLayout>
    );
}
