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
                <div className="music-detail-media group relative">
                    <div className="absolute inset-0 bg-gradient-to-b from-fuchsia-500/20 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100 rounded-xl blur-xl"></div>
                    <div className="music-detail-image-frame relative z-10 border border-white/10 shadow-2xl shadow-black">
                        <img src={artist.photo_url} alt={artist.name} className="music-detail-image" />
                    </div>
                </div>
                <div className="rounded-2xl border border-white/5 bg-slate-900/40 p-8 backdrop-blur-xl shadow-2xl shadow-black/40 relative overflow-hidden">
                    <div className="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-violet-600/10 blur-[80px]" />
                    
                    <div className="relative z-10">
                        <p className="inline-block rounded-full border border-violet-500/20 bg-violet-500/10 px-3 py-1 text-xs font-bold uppercase tracking-widest text-violet-300 shadow-inner backdrop-blur-sm">
                            {artist.country || 'Unknown'} <span className="mx-1 opacity-50">·</span> {artist.artist_type_label}
                        </p>
                        <h1 className="mt-4 text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-200 to-slate-400 tracking-tight">{artist.name}</h1>
                        <p className="mt-5 max-w-3xl text-lg text-slate-300 leading-relaxed font-medium bg-white/5 p-5 rounded-xl border border-white/5 backdrop-blur-sm">
                            {artist.bio || 'Biography will appear here after the artist is updated by an administrator.'}
                        </p>
                        
                        <dl className="mt-8 grid gap-4 text-sm md:grid-cols-2 lg:grid-cols-4">
                            {[
                                ['Company', artist.company || 'Unknown'],
                                ['Debut', artist.debut_date_label || 'Unknown'],
                                ['Members', artist.members_count || 'Unknown'],
                                ['Fandom', artist.fandom_name || 'Unknown'],
                            ].map(([term, value]) => (
                                <div key={term} className="rounded-xl border border-white/5 bg-white/5 p-4 backdrop-blur-sm transition-transform hover:-translate-y-1 hover:bg-white/10 shadow-inner">
                                    <dt className="text-xs font-semibold text-slate-400 uppercase tracking-wider">{term}</dt>
                                    <dd className="mt-1.5 text-lg font-bold text-slate-100">{value}</dd>
                                </div>
                            ))}
                        </dl>
                        
                        <div className="mt-8 flex flex-wrap items-center gap-3">
                            <div className="flex items-center gap-2 rounded-lg bg-black/40 px-4 py-2 border border-white/5 shadow-inner">
                                <span className="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span className="text-sm font-bold text-slate-200">{artist.followers_count.toLocaleString()} followers</span>
                            </div>
                            
                            {artist.official_site && (
                                <a href={artist.official_site} target="_blank" rel="noopener noreferrer" className="inline-flex min-h-10 items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-200 backdrop-blur-sm transition-all hover:border-violet-500/50 hover:bg-white/10 hover:text-white hover:shadow-[0_0_15px_rgba(139,92,246,0.2)]">
                                    <ExternalLink size={16} /> Site
                                </a>
                            )}
                            {artist.spotify_url && (
                                <a href={artist.spotify_url} target="_blank" rel="noopener noreferrer" className="inline-flex min-h-10 items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-200 backdrop-blur-sm transition-all hover:border-green-500/50 hover:bg-white/10 hover:text-white hover:shadow-[0_0_15px_rgba(34,197,94,0.2)]">
                                    <Music2 size={16} /> Spotify
                                </a>
                            )}
                            {artist.instagram_url && (
                                <a href={artist.instagram_url} target="_blank" rel="noopener noreferrer" className="inline-flex min-h-10 items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-200 backdrop-blur-sm transition-all hover:border-pink-500/50 hover:bg-white/10 hover:text-white hover:shadow-[0_0_15px_rgba(236,72,153,0.2)]">
                                    <Camera size={16} /> Insta
                                </a>
                            )}
                        </div>
                        
                        {auth.user && (
                            <Button
                                className={`mt-8 ${isFavorite ? 'shadow-[0_0_15px_rgba(139,92,246,0.4)]' : ''}`}
                                variant={isFavorite ? 'primary' : 'secondary'}
                                type="button"
                                onClick={() => router.post(`/favorites/${artist.id}`)}
                                aria-pressed={isFavorite}
                            >
                                <Heart size={18} fill={isFavorite ? 'currentColor' : 'none'} className={isFavorite ? 'text-white' : 'text-slate-400'} />
                                {isFavorite ? 'Following Artist' : 'Follow Artist'}
                            </Button>
                        )}
                    </div>
                </div>
            </section>
            
            <section className="mt-16">
                <div className="mb-8 flex items-end justify-between">
                    <div>
                        <h2 className="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400">Releases by {artist.name}</h2>
                        <p className="mt-2 text-base text-slate-400">Explore the complete catalog and release history.</p>
                    </div>
                </div>
                <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    {artist.albums.length ? artist.albums.map((album) => <AlbumCard key={album.id} album={album} compact />) : <EmptyState className="col-span-full border-white/5 bg-slate-900/40 backdrop-blur-md">This artist has no releases yet.</EmptyState>}
                </div>
            </section>
        </AppLayout>
    );
}