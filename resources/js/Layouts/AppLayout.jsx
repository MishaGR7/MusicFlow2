import { Head, Link, usePage } from '@inertiajs/react';
import { Bell, Disc3, Heart, LayoutDashboard, LogOut, User } from 'lucide-react';
import Button from '../Components/Button';

function NavLink({ href, children }) {
    const { url } = usePage();
    const active = url === href || (href !== '/' && url.startsWith(href));

    return (
        <Link className={`music-nav-link ${active ? 'text-violet-300' : ''}`} href={href}>
            {children}
        </Link>
    );
}

export default function AppLayout({ title, children }) {
    const { auth, flash, unreadNotifications = [] } = usePage().props;

    return (
        <>
            <Head title={title} />
            <div className="min-h-screen bg-slate-950/95 text-slate-100">
                <header className="sticky top-0 z-20 border-b border-slate-800 bg-slate-950/95 backdrop-blur">
                    <div className="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-4 sm:flex-nowrap sm:px-6 lg:px-8">
                        <Link href="/" className="text-2xl font-semibold tracking-wide text-violet-400">MusicFlow</Link>
                        <nav className="flex flex-wrap items-center justify-end gap-2 text-sm sm:gap-4">
                            <NavLink href="/">Home</NavLink>
                            <NavLink href="/releases">Releases</NavLink>
                            <NavLink href="/artists">Artists</NavLink>
                            {auth.user ? (
                                <>
                                    <NavLink href="/my-favorites">My Favorites</NavLink>
                                    <NavLink href="/profile">
                                        Profile
                                        {auth.user.unread_notifications_count > 0 && (
                                            <span className="ml-1 rounded-full bg-violet-600 px-2 py-0.5 text-xs text-white">{auth.user.unread_notifications_count}</span>
                                        )}
                                    </NavLink>
                                    {auth.user.is_admin && (
                                        <>
                                            <NavLink href="/admin/albums"><LayoutDashboard className="inline" size={15} /> Admin</NavLink>
                                            <NavLink href="/admin/artists">Artists</NavLink>
                                        </>
                                    )}
                                    <Button href="/logout" method="post" as="button">
                                        <LogOut size={16} /> Logout
                                    </Button>
                                </>
                            ) : (
                                <>
                                    <NavLink href="/login">Login</NavLink>
                                    <Button href="/register" variant="primary">Register</Button>
                                </>
                            )}
                        </nav>
                    </div>
                </header>

                <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    {flash.status && (
                        <div className="mb-6 rounded-lg border border-emerald-700 bg-emerald-900/40 px-4 py-3 text-sm text-emerald-200">
                            {flash.status}
                        </div>
                    )}

                    {auth.user && unreadNotifications.length > 0 && (
                        <div className="mb-6 rounded-lg border border-violet-800 bg-violet-900/30 px-4 py-3 text-sm">
                            <div className="mb-2 flex items-center justify-between gap-3">
                                <p className="flex items-center gap-2 font-semibold text-violet-100"><Bell size={16} /> New updates from your favorite artists</p>
                                <Link href="/profile" className="text-violet-200 underline underline-offset-4">Open profile</Link>
                            </div>
                            <div className="space-y-1">
                                {unreadNotifications.map((notification) => (
                                    <p key={notification.id}>
                                        {notification.message}
                                        {notification.action_url && (
                                            <Link href={notification.action_url} className="ml-2 text-violet-200 underline underline-offset-4">
                                                {notification.action_label || 'Open'}
                                            </Link>
                                        )}
                                    </p>
                                ))}
                            </div>
                        </div>
                    )}

                    {children}
                </main>
            </div>
        </>
    );
}
