import { usePage } from '@inertiajs/react';
import { ChevronLeft, ChevronRight, Disc3, Mic2, UserPlus } from 'lucide-react';
import { useRef } from 'react';
import AlbumCard from '../Components/AlbumCard';
import ArtistCard from '../Components/ArtistCard';
import Button from '../Components/Button';
import EmptyState from '../Components/EmptyState';
import AppLayout from '../Layouts/AppLayout';

function HorizontalShelf({ children }) {
    const scrollRef = useRef(null);

    const scroll = (direction) => {
        const container = scrollRef.current;

        if (!container) {
            return;
        }

        container.scrollBy({
            left: direction * Math.min(container.clientWidth * 0.9, 720),
            behavior: 'smooth',
        });
    };

    return (
        <div className="group relative -mx-4 sm:-mx-6 lg:-mx-8">
            <div
                ref={scrollRef}
                className="music-scrollbar-hidden overflow-x-auto scroll-smooth px-4 pb-1 sm:px-6 lg:px-8"
            >
                <div className="flex snap-x snap-mandatory gap-5">
                    {children}
                </div>
            </div>
            <div className="pointer-events-none absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-slate-950/95 to-transparent" />
            <div className="pointer-events-none absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-slate-950/95 to-transparent" />
            <div className="pointer-events-none absolute inset-y-0 left-2 hidden items-center opacity-0 transition group-hover:opacity-100 md:flex">
                <button
                    type="button"
                    onClick={() => scroll(-1)}
                    className="pointer-events-auto flex h-10 w-10 items-center justify-center rounded-full border border-slate-700 bg-slate-950/90 text-slate-200 shadow-lg shadow-black/30 transition hover:border-violet-500 hover:text-violet-200"
                    aria-label="Scroll left"
                >
                    <ChevronLeft size={18} />
                </button>
            </div>
            <div className="pointer-events-none absolute inset-y-0 right-2 hidden items-center opacity-0 transition group-hover:opacity-100 md:flex">
                <button
                    type="button"
                    onClick={() => scroll(1)}
                    className="pointer-events-auto flex h-10 w-10 items-center justify-center rounded-full border border-slate-700 bg-slate-950/90 text-slate-200 shadow-lg shadow-black/30 transition hover:border-violet-500 hover:text-violet-200"
                    aria-label="Scroll right"
                >
                    <ChevronRight size={18} />
                </button>
            </div>
        </div>
    );
}

export default function Home({ latestAlbums = [], featuredArtists = [], stats = {} }) {
    const { auth } = usePage().props;

    return (
        <AppLayout title="Home">
            <section className="mb-8 overflow-hidden rounded-lg border border-slate-800 bg-gradient-to-br from-slate-900 via-slate-950 to-violet-950/80 p-8">
                <p className="text-sm uppercase tracking-[0.3em] text-violet-300">MusicFlow</p>
                <h1 className="mt-4 max-w-3xl text-4xl font-bold text-white md:text-5xl">Track releases, follow artists, and keep your music portfolio project polished.</h1>
                <p className="mt-4 max-w-2xl text-base text-slate-300">Discover releases, browse artists, manage favorites, and keep profile settings in one place.</p>
                <div className="mt-6 flex flex-wrap gap-3">
                    <Button href="/releases" variant="primary"><Disc3 size={16} /> Releases</Button>
                    <Button href="/artists"><Mic2 size={16} /> Artists</Button>
                    {auth.user ? (
                        <Button href="/profile"><UserPlus size={16} /> Open Profile</Button>
                    ) : (
                        <Button href="/register"><UserPlus size={16} /> Create Account</Button>
                    )}
                </div>
                <div className="mt-8 grid gap-4 md:grid-cols-3">
                    {[
                        ['Artists', stats.artists],
                        ['Releases', stats.albums],
                        ['Published', stats.published],
                    ].map(([label, value]) => (
                        <div key={label} className="rounded-lg border border-slate-800 bg-black/20 p-4">
                            <p className="text-sm text-slate-400">{label}</p>
                            <p className="mt-2 text-3xl font-semibold text-white">{value}</p>
                        </div>
                    ))}
                </div>
            </section>

            <section className="mb-10">
                <div className="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-white">Latest Releases</h2>
                        <p className="text-sm text-slate-400">A quick preview from the release catalog.</p>
                    </div>
                    <Button href="/releases">See all releases</Button>
                </div>
                {latestAlbums.length ? (
                    <HorizontalShelf>
                        {latestAlbums.map((album) => (
                            <AlbumCard key={album.id} album={album} className="w-[min(82vw,340px)] shrink-0 snap-start" />
                        ))}
                    </HorizontalShelf>
                ) : <EmptyState>No releases yet.</EmptyState>}
            </section>

            <section>
                <div className="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-white">Featured Artists</h2>
                        <p className="text-sm text-slate-400">Public artist pages show catalog details and release history.</p>
                    </div>
                    <Button href="/artists">See all artists</Button>
                </div>
                {featuredArtists.length ? (
                    <HorizontalShelf>
                        {featuredArtists.map((artist) => (
                            <ArtistCard key={artist.id} artist={artist} className="w-[min(82vw,340px)] shrink-0 snap-start" />
                        ))}
                    </HorizontalShelf>
                ) : <EmptyState>No artists yet.</EmptyState>}
            </section>
        </AppLayout>
    );
}
