import { router, usePage } from '@inertiajs/react';
import { Camera, ExternalLink, Heart, Music2 } from 'lucide-react';
import AlbumCard from '../../Components/AlbumCard';
import Button from '../../Components/Button';
import EmptyState from '../../Components/EmptyState';
import AppLayout from '../../Layouts/AppLayout';

export default function Show({ artist }) {
    const { auth, favoriteArtistIds = [] } = usePage().props;
    const isFavorite = favoriteArtistIds.includes(artist.id);

    return (
        <AppLayout title={artist.name}>
            <section className="grid gap-8 lg:grid-cols-[320px_minmax(0,1fr)]">
                <div className="music-detail-media">
                    <div className="music-detail-image-frame">
                        <img src={artist.photo_url} alt={artist.name} className="music-detail-image" />
                    </div>
                </div>
                <div className="rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                    <p className="text-sm uppercase tracking-[0.3em] text-violet-300">{artist.country || 'Unknown country'} · {artist.artist_type_label}</p>
                    <h1 className="mt-3 text-4xl font-bold text-white">{artist.name}</h1>
                    <p className="mt-4 max-w-3xl text-slate-300">{artist.bio || 'Biography will appear here after the artist is updated by an administrator.'}</p>
                    <dl className="mt-6 grid gap-4 text-sm md:grid-cols-2">
                        {[
                            ['Company', artist.company || 'Unknown'],
                            ['Debut', artist.debut_date_label || 'Unknown'],
                            ['Members', artist.members_count || 'Unknown'],
                            ['Fandom', artist.fandom_name || 'Unknown'],
                        ].map(([term, value]) => (
                            <div key={term} className="rounded-lg border border-slate-800 bg-slate-950/60 p-4">
                                <dt className="text-slate-500">{term}</dt>
                                <dd className="mt-1 text-slate-200">{value}</dd>
                            </div>
                        ))}
                    </dl>
                    <div className="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-400">
                        <p>{artist.followers_count} followers</p>
                        {artist.official_site && (
                            <a href={artist.official_site} target="_blank" rel="noopener noreferrer" className="inline-flex min-h-8 items-center gap-1.5 rounded-lg border border-slate-700 bg-slate-950 px-3 py-1.5 text-xs font-semibold text-slate-200 transition hover:border-violet-500 hover:text-violet-200">
                                <ExternalLink size={14} /> Official site
                            </a>
                        )}
                        {artist.spotify_url && (
                            <a href={artist.spotify_url} target="_blank" rel="noopener noreferrer" className="inline-flex min-h-8 items-center gap-1.5 rounded-lg border border-slate-700 bg-slate-950 px-3 py-1.5 text-xs font-semibold text-slate-200 transition hover:border-violet-500 hover:text-violet-200">
                                <Music2 size={14} /> Spotify
                            </a>
                        )}
                        {artist.instagram_url && (
                            <a href={artist.instagram_url} target="_blank" rel="noopener noreferrer" className="inline-flex min-h-8 items-center gap-1.5 rounded-lg border border-slate-700 bg-slate-950 px-3 py-1.5 text-xs font-semibold text-slate-200 transition hover:border-violet-500 hover:text-violet-200">
                                <Camera size={14} /> Instagram
                            </a>
                        )}
                    </div>
                    {auth.user && (
                        <Button
                            className="mt-6"
                            variant={isFavorite ? 'primary' : 'secondary'}
                            type="button"
                            onClick={() => router.post(`/favorites/${artist.id}`)}
                            aria-pressed={isFavorite}
                        >
                            <Heart size={16} fill={isFavorite ? 'currentColor' : 'none'} />
                            {isFavorite ? 'Following' : 'Follow artist'}
                        </Button>
                    )}
                </div>
            </section>
            <section className="mt-10">
                <div className="mb-6">
                    <h2 className="text-2xl font-bold text-white">Releases by {artist.name}</h2>
                    <p className="text-sm text-slate-400">Release history connected to this artist.</p>
                </div>
                <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    {artist.albums.length ? artist.albums.map((album) => <AlbumCard key={album.id} album={album} compact />) : <EmptyState>This artist has no releases yet.</EmptyState>}
                </div>
            </section>
        </AppLayout>
    );
}
