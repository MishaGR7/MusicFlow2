import { useForm } from '@inertiajs/react';
import { Filter, RotateCcw } from 'lucide-react';
import AlbumCard from '../../Components/AlbumCard';
import Button from '../../Components/Button';
import EmptyState from '../../Components/EmptyState';
import { Checkbox, Input, Select } from '../../Components/Form';
import Pagination from '../../Components/Pagination';
import AppLayout from '../../Layouts/AppLayout';

export default function Index({ albums, favoriteArtistIds = [], countries = [], statuses = [], filters = {}, canFilterFavorites = false }) {
    const { data, setData, get } = useForm({
        q: filters.search || '',
        status: filters.status || '',
        country: filters.country || '',
        favorites: filters.onlyFavorites ? '1' : '',
    });

    const submit = (event) => {
        event.preventDefault();
        get('/releases', { preserveState: true, preserveScroll: true });
    };

    return (
        <AppLayout title="Release Catalog">
            <section className="mb-8 rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <h1 className="text-3xl font-bold text-white">Release Catalog</h1>
                <p className="mt-2 text-sm text-slate-400">Browse all releases, filter by status or country, and focus on artists you follow.</p>
                <form onSubmit={submit} className="mt-6 grid gap-4 md:grid-cols-4">
                    <Input className="md:col-span-2" value={data.q} onChange={(e) => setData('q', e.target.value)} placeholder="Search by album or artist" />
                    <Select value={data.status} onChange={(e) => setData('status', e.target.value)}>
                        <option value="">All statuses</option>
                        {statuses.map((value) => <option key={value} value={value}>{value[0].toUpperCase() + value.slice(1)}</option>)}
                    </Select>
                    <Select value={data.country} onChange={(e) => setData('country', e.target.value)}>
                        <option value="">All countries</option>
                        {countries.map((value) => <option key={value} value={value}>{value}</option>)}
                    </Select>
                    {canFilterFavorites && (
                        <Checkbox
                            className="md:col-span-2"
                            label="Only my favorite artists"
                            checked={data.favorites === '1'}
                            onChange={(e) => setData('favorites', e.target.checked ? '1' : '')}
                        />
                    )}
                    <div className="flex flex-wrap gap-3 md:col-span-4">
                        <Button type="submit" variant="primary"><Filter size={16} /> Apply Filters</Button>
                        <Button href="/releases"><RotateCcw size={16} /> Reset</Button>
                    </div>
                </form>
            </section>
            <section className="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                {albums.data.length ? albums.data.map((album) => <AlbumCard key={album.id} album={album} favoriteArtistIds={favoriteArtistIds} />) : <EmptyState>No releases found for this filter set.</EmptyState>}
            </section>
            <Pagination links={albums.links} />
        </AppLayout>
    );
}
