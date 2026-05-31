import EmptyState from '../../Components/EmptyState';
import AppLayout from '../../Layouts/AppLayout';

export default function Index({ favorites = [] }) {
    return (
        <AppLayout title="My Favorites">
            <h1 className="mb-6 text-3xl font-bold text-white">My Favorites</h1>
            <div className="grid gap-6 md:grid-cols-2">
                {favorites.length ? favorites.map((artist) => (
                    <article key={artist.id} className="music-card">
                        <div className="flex items-center gap-4">
                            <img src={artist.photo_url} className="h-16 w-16 rounded-full object-cover" alt={artist.name} />
                            <div>
                                <h2 className="text-lg font-semibold text-white">{artist.name}</h2>
                                <p className="text-sm text-slate-400">{artist.country || 'Unknown country'}</p>
                            </div>
                        </div>
                        <p className="mt-4 text-sm text-slate-300">{artist.bio || 'No biography yet.'}</p>
                        <div className="mt-4 space-y-2 text-sm">
                            {artist.albums.length ? artist.albums.map((album) => (
                                <div key={album.id} className="rounded-lg border border-slate-800 bg-slate-950/80 px-3 py-2 text-slate-300">
                                    {album.title} <span className="text-slate-500">({album.release_label})</span>
                                </div>
                            )) : <p className="text-slate-500">No albums yet.</p>}
                        </div>
                    </article>
                )) : <EmptyState>You have no favorite artists yet.</EmptyState>}
            </div>
        </AppLayout>
    );
}
