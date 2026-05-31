import { Link } from '@inertiajs/react';
import AlbumCover from '../../Components/AlbumCover';
import Badge from '../../Components/Badge';
import Button from '../../Components/Button';
import { getTrackCountLabel } from '../../Components/TrackCount';
import AppLayout from '../../Layouts/AppLayout';

export default function Show({ album }) {
    const trackCountLabel = getTrackCountLabel(album.tracks.length);

    return (
        <AppLayout title={album.title}>
            <article className="grid gap-8 lg:grid-cols-[320px_minmax(0,1fr)]">
                <div className="music-detail-media group relative">
                    <div className="absolute inset-0 bg-gradient-to-b from-violet-500/20 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100 rounded-xl blur-xl"></div>
                    <div className="music-detail-image-frame relative z-10 border border-white/10 shadow-2xl shadow-black">
                        <AlbumCover album={album} imageClassName="music-detail-image" frameClassName="h-full w-full" />
                    </div>
                </div>
                <div className="rounded-2xl border border-white/5 bg-slate-900/40 p-8 backdrop-blur-xl shadow-2xl shadow-black/40 relative overflow-hidden">
                    <div className="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-violet-500/10 blur-[60px]" />
                    
                    <div className="relative z-10">
                        <Badge status={album.status} />
                        <h1 className="mt-4 text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-300 tracking-tight">{album.title}</h1>
                        <p className="mt-3 text-xl text-slate-300 font-medium">
                            by <Link href={`/artists/${album.artist.id}`} className="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-400 hover:from-violet-300 hover:to-fuchsia-300 transition-all font-bold drop-shadow-sm">{album.artist.name}</Link>
                        </p>
                        
                        <div className="mt-6 flex flex-col gap-2 bg-white/5 border border-white/5 rounded-xl p-4 backdrop-blur-sm">
                            <p className="text-sm text-slate-400 flex justify-between"><span className="text-slate-500">Release date:</span> <span className="text-slate-200 font-medium">{album.release_label}</span></p>
                            <p className="text-sm text-slate-400 flex justify-between"><span className="text-slate-500">Country:</span> <span className="text-slate-200 font-medium">{album.artist.country || 'Unknown'}</span></p>
                            {trackCountLabel && <p className="text-sm text-slate-400 flex justify-between"><span className="text-slate-500">Tracks:</span> <span className="text-slate-200 font-medium">{trackCountLabel}</span></p>}
                        </div>

                        {album.spotify_url && <Button href={album.spotify_url} className="mt-8 shadow-[0_0_15px_rgba(139,92,246,0.3)]">Open on Spotify</Button>}
                    </div>
                </div>
            </article>
            
            {album.tracks.length > 0 && (
                <section className="mt-12 rounded-2xl border border-white/5 bg-slate-900/40 p-8 backdrop-blur-md shadow-xl">
                    <h2 className="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 mb-6 inline-block">Track list</h2>
                    <div className="divide-y divide-white/5">
                        {album.tracks.map((track) => (
                        <div key={track.id} className="grid gap-4 py-4 px-2 -mx-2 text-sm md:grid-cols-[48px_minmax(0,1fr)_100px_140px] md:items-center hover:bg-white/5 rounded-xl transition-colors duration-200 group">
                            <p className="font-semibold text-slate-500 group-hover:text-violet-400 transition-colors">{(track.position).toString().padStart(2, '0')}</p>
                            <p className="text-slate-200 font-medium text-base">{track.title}</p>
                            <p className="text-slate-400 font-mono">{track.duration || '--:--'}</p>
                            <div>
                                {track.is_title_track && <span className="inline-flex items-center justify-center rounded-full border border-violet-500/30 bg-violet-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-violet-300 shadow-inner">Title track</span>}
                            </div>
                        </div>
                        ))}
                    </div>
                </section>
            )}
        </AppLayout>
    );
}
