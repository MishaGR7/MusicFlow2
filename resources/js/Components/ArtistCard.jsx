import { router, usePage } from '@inertiajs/react';
import { Eye, Heart } from 'lucide-react';
import Button from './Button';

export default function ArtistCard({ artist, className = '' }) {
    const { auth, favoriteArtistIds = [] } = usePage().props;
    const isFavorite = favoriteArtistIds.includes(artist.id);

    return (
        <article className={`music-card ${className}`.trim()}>
            <img src={artist.photo_url} alt={artist.name} className="h-56 w-full rounded-lg object-cover" />
            <h2 className="mt-4 text-xl font-semibold text-white">{artist.name}</h2>
            <p className="mt-1 text-sm text-slate-400">{artist.country || 'Unknown country'} · {artist.artist_type_label}</p>
            <p className="mt-2 text-sm text-slate-300">{artist.company || 'Independent / unknown company'}</p>
            {artist.debut_date_label && <p className="mt-1 text-sm text-slate-400">Debut: {artist.debut_date_label}</p>}
            <p className="mt-3 text-sm text-slate-300">{artist.albums_count} releases · {artist.followers_count} followers</p>
            <p className="mt-3 line-clamp-3 text-sm text-slate-400">{artist.bio || 'No biography yet.'}</p>
            <div className="mt-4 flex flex-wrap gap-3">
                <Button href={`/artists/${artist.id}`}>
                    <Eye size={16} /> View profile
                </Button>
                {auth.user && (
                    <Button
                        type="button"
                        variant={isFavorite ? 'primary' : 'secondary'}
                        onClick={() => router.post(`/favorites/${artist.id}`, {}, { preserveScroll: true })}
                        aria-pressed={isFavorite}
                    >
                        <Heart size={16} fill={isFavorite ? 'currentColor' : 'none'} />
                        {isFavorite ? 'Following' : 'Follow artist'}
                    </Button>
                )}
            </div>
        </article>
    );
}
