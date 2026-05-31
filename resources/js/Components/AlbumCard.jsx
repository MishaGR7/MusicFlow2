import { Link } from '@inertiajs/react';
import { Disc3 } from 'lucide-react';
import AlbumCover from './AlbumCover';
import Badge from './Badge';
import Button from './Button';
import TrackCount from './TrackCount';

export default function AlbumCard({ album, compact = false, className = '' }) {
    return (
        <article className={`music-card ${className}`.trim()}>
            <AlbumCover album={album} className={`${compact ? 'h-48' : 'h-56'} w-full rounded-lg object-cover`} />
            <div className="mt-4 flex items-start justify-between gap-4">
                <div>
                    <h2 className="text-xl font-semibold text-white">{album.title}</h2>
                    {album.artist && (
                        <p className="text-sm text-slate-400">
                            <Link href={`/artists/${album.artist.id}`} className="hover:text-violet-300">{album.artist.name}</Link>
                            {album.artist.country ? ` · ${album.artist.country}` : ''}
                        </p>
                    )}
                </div>
                <Badge status={album.status} />
            </div>
            <p className="mt-2 text-xs text-slate-500">Release: {album.release_label}</p>
            <TrackCount count={album.tracks_count} className="mt-2 text-sm text-slate-300" />
            {album.title_track && <p className="text-sm text-violet-300">Title track: {album.title_track.title}</p>}
            <div className="mt-4 flex flex-wrap gap-3">
                <Button href={`/releases/${album.id}`}>
                    <Disc3 size={16} /> View
                </Button>
            </div>
        </article>
    );
}
