import { useForm } from '@inertiajs/react';
import { Filter, RotateCcw } from 'lucide-react';
import ArtistCard from '../../Components/ArtistCard';
import Button from '../../Components/Button';
import EmptyState from '../../Components/EmptyState';
import { Input, Select } from '../../Components/Form';
import Pagination from '../../Components/Pagination';
import AppLayout from '../../Layouts/AppLayout';

export default function Index({ artists, countries = [], companies = [], types = [], filters = {} }) {
    const { data, setData, get } = useForm({
        q: filters.search || '',
        country: filters.country || '',
        company: filters.company || '',
        type: filters.type || '',
    });

    const submit = (event) => {
        event.preventDefault();
        get('/artists', { preserveState: true, preserveScroll: true });
    };

    return (
        <AppLayout title="Artists">
            <section className="mb-8 rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <h1 className="text-3xl font-bold text-white">Artists</h1>
                <p className="mt-2 text-sm text-slate-400">Public pages for every artist, with release history and follower actions.</p>
                <form onSubmit={submit} className="mt-6 grid gap-4 md:grid-cols-4">
                    <Input className="md:col-span-2" name="q" value={data.q} onChange={(e) => setData('q', e.target.value)} placeholder="Search artists, company, fandom" />
                    <Select value={data.country} onChange={(e) => setData('country', e.target.value)}>
                        <option value="">All countries</option>
                        {countries.map((value) => <option key={value} value={value}>{value}</option>)}
                    </Select>
                    <Select value={data.company} onChange={(e) => setData('company', e.target.value)}>
                        <option value="">All companies</option>
                        {companies.map((value) => <option key={value} value={value}>{value}</option>)}
                    </Select>
                    <Select value={data.type} onChange={(e) => setData('type', e.target.value)}>
                        <option value="">All types</option>
                        {types.map((value) => <option key={value} value={value}>{value[0].toUpperCase() + value.slice(1)}</option>)}
                    </Select>
                    <div className="flex flex-wrap gap-3 md:col-span-4">
                        <Button type="submit" variant="primary"><Filter size={16} /> Apply Filters</Button>
                        <Button href="/artists"><RotateCcw size={16} /> Reset</Button>
                    </div>
                </form>
            </section>
            <section className="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                {artists.data.length ? artists.data.map((artist) => <ArtistCard key={artist.id} artist={artist} />) : <EmptyState>No artists found.</EmptyState>}
            </section>
            <Pagination links={artists.links} />
        </AppLayout>
    );
}
