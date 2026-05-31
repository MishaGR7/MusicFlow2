import { Link } from '@inertiajs/react';
import Badge from '../../Components/Badge';
import Button from '../../Components/Button';
import { getTrackCountLabel } from '../../Components/TrackCount';
import AppLayout from '../../Layouts/AppLayout';

export default function Show({ album }) {
    const trackCountLabel = getTrackCountLabel(album.tracks.length);

    return (
        <AppLayout title={album.title}>
            <article className="grid gap-8 lg:grid-cols-[320px_minmax(0,1fr)]">
                <div className="music-detail-media">
                    <div className="music-detail-image-frame">
                        <img src={album.cover_url} alt={album.title} className="music-detail-image" />
                    </div>
                </div>
                <div className="rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                    <Badge status={album.status} />
                    <h1 className="mt-3 text-4xl font-bold text-white">{album.title}</h1>
                    <p className="mt-3 text-lg text-slate-300">
                        by <Link href={`/artists/${album.artist.id}`} className="text-violet-300 hover:text-violet-200">{album.artist.name}</Link>
                    </p>
                    <p className="mt-2 text-sm text-slate-400">Release date: {album.release_label}</p>
                    <p className="mt-2 text-sm text-slate-400">Country: {album.artist.country || 'Unknown'}</p>
                    {trackCountLabel && <p className="mt-2 text-sm text-slate-400">{trackCountLabel}</p>}
                    {album.spotify_url && <Button href={album.spotify_url} className="mt-6">Open on Spotify</Button>}
                </div>
            </article>
            {album.tracks.length > 0 && (
                <section className="mt-10 rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                    <h2 className="text-2xl font-bold text-white">Track list</h2>
                    <div className="mt-5 divide-y divide-slate-800">
                        {album.tracks.map((track) => (
                        <div key={track.id} className="grid gap-3 py-3 text-sm md:grid-cols-[56px_minmax(0,1fr)_120px_140px] md:items-center">
                            <p className="font-semibold text-slate-500">#{track.position}</p>
                            <p className="text-slate-100">{track.title}</p>
                            <p className="text-slate-400">{track.duration || '-'}</p>
                            {track.is_title_track && <span className="w-fit rounded-full bg-violet-900/50 px-3 py-1 text-xs font-semibold text-violet-200">Title track</span>}
                        </div>
                        ))}
                    </div>
                </section>
            )}
        </AppLayout>
    );
}
