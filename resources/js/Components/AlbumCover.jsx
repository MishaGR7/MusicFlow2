export default function AlbumCover({ album, className = '', imageClassName = '', frameClassName = '' }) {
    const label = album.title || 'Untitled Album';

    if (album.cover_url) {
        return <img src={album.cover_url} alt={label} className={imageClassName || className} />;
    }

    return (
        <div
            className={`bg-slate-950 shadow-inner shadow-black/60 ${frameClassName || className}`.trim()}
            role="img"
            aria-label={label}
        />
    );
}
