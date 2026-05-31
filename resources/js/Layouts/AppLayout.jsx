import { Head, Link, usePage } from '@inertiajs/react';
import { Bell, Disc3, LayoutDashboard, LogOut, UsersRound } from 'lucide-react';

function NavLink({ href, children }) {
    const { url } = usePage();
    const active = url === href || (href !== '/' && url.startsWith(href));

    return (
        <Link 
            className={`flex items-center px-3 py-1.5 rounded-xl font-medium text-sm transition-all duration-300 ${
                active 
                    ? 'bg-violet-500/10 text-violet-300 border border-violet-500/20 shadow-[0_0_15px_rgba(139,92,246,0.15)] shadow-inner' 
                    : 'text-slate-400 border border-transparent hover:text-violet-200 hover:bg-white/5'
            }`} 
            href={href}
        >
            {children}
        </Link>
    );
}

export default function AppLayout({ title, children }) {
    const { auth, flash, unreadNotifications = [] } = usePage().props;

    return (
        <>
            <Head title={title} />
            <div className="music-body relative min-h-screen">
                {/* Ambient Background Glow */}
                <div className="pointer-events-none fixed inset-0 z-0 overflow-hidden">
                    <div className="absolute -top-[20%] -left-[10%] h-[50%] w-[50%] rounded-full bg-violet-900/20 blur-[120px]" />
                    <div className="absolute top-[40%] -right-[10%] h-[40%] w-[40%] rounded-full bg-blue-900/10 blur-[120px]" />
                    <div className="absolute bottom-[0%] left-[20%] h-[30%] w-[30%] rounded-full bg-fuchsia-900/10 blur-[100px]" />
                </div>

                <div className="relative z-10 flex flex-col min-h-screen">
                    {/* Floating Pill Navigation */}
                    <div className="sticky top-4 z-50 px-4 sm:px-6 lg:px-8 w-full max-w-7xl mx-auto mt-4">
                        <header className="rounded-2xl border border-white/10 bg-slate-900/60 backdrop-blur-2xl shadow-2xl shadow-black/50 transition-all">
                            <div className="flex flex-wrap items-center justify-between gap-3 px-4 py-3 sm:flex-nowrap">
                                <Link href="/" className="group flex items-center gap-2.5 transition-transform hover:scale-[1.02]">
                                    <div className="relative flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl border border-white/15 bg-slate-950 shadow-lg shadow-violet-950/40">
                                        <div className="absolute inset-0 bg-[radial-gradient(circle_at_30%_25%,rgba(255,255,255,0.35),transparent_28%),linear-gradient(135deg,rgba(34,211,238,0.9),rgba(139,92,246,0.95)_48%,rgba(236,72,153,0.9))]" />
                                        <div className="absolute inset-[3px] rounded-lg border border-white/20 bg-slate-950/30" />
                                        <Disc3 size={22} className="relative text-white drop-shadow-[0_0_8px_rgba(255,255,255,0.45)] transition-transform duration-500 group-hover:rotate-45" />
                                        <span className="absolute right-1.5 top-2 h-3 w-1 rounded-full bg-cyan-200/90 shadow-[0_0_12px_rgba(103,232,249,0.8)]" />
                                    </div>
                                    <span className="flex flex-col leading-none">
                                        <span className="bg-gradient-to-r from-white via-cyan-100 to-fuchsia-200 bg-clip-text text-xl font-black tracking-normal text-transparent drop-shadow-sm">
                                            MusicFlow
                                        </span>
                                        <span className="mt-1 h-px w-full rounded-full bg-gradient-to-r from-cyan-300/80 via-violet-300/70 to-fuchsia-300/80 opacity-80" />
                                    </span>
                                </Link>
                                <nav className="flex flex-wrap items-center justify-end gap-1 sm:gap-2">
                                    <NavLink href="/">Home</NavLink>
                                    <NavLink href="/releases">Releases</NavLink>
                                    <NavLink href="/artists">Artists</NavLink>
                                    {auth.user ? (
                                        <>
                                            <NavLink href="/my-favorites">Favorites</NavLink>
                                            <NavLink href="/profile">
                                                Profile
                                                {auth.user.unread_notifications_count > 0 && (
                                                    <span className="ml-1.5 rounded-full bg-violet-500 px-2 py-0.5 text-[10px] font-bold text-white shadow-[0_0_8px_rgba(139,92,246,0.8)]">{auth.user.unread_notifications_count}</span>
                                                )}
                                            </NavLink>
                                            {auth.user.is_admin && (
                                                <>
                                                    <NavLink href="/admin/albums"><LayoutDashboard className="mr-1" size={14} /> Admin</NavLink>
                                                    <NavLink href="/admin/artists"><UsersRound className="mr-1" size={14} /> Artists</NavLink>
                                                </>
                                            )}
                                            <Link href="/logout" method="post" as="button" className="ml-2 flex items-center justify-center h-8 w-8 rounded-full bg-white/5 border border-white/10 text-slate-300 transition-all hover:bg-rose-500/20 hover:text-rose-300 hover:border-rose-500/30" title="Logout">
                                                <LogOut size={14} />
                                            </Link>
                                        </>
                                    ) : (
                                        <>
                                            <div className="w-px h-5 bg-white/10 mx-2 hidden sm:block"></div>
                                            <Link href="/login" className="text-sm font-medium text-slate-300 hover:text-white px-3 transition-colors">Login</Link>
                                            <Link href="/register" className="inline-flex h-8 items-center justify-center rounded-lg bg-gradient-to-r from-violet-600 to-fuchsia-600 px-4 text-sm font-semibold text-white shadow-lg shadow-violet-900/30 transition-all hover:scale-105 hover:shadow-violet-500/40">Register</Link>
                                        </>
                                    )}
                                </nav>
                            </div>
                        </header>
                    </div>

                    <main className="flex-1 mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8 mt-4">
                        {flash.status && (
                            <div className="mb-6 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200 backdrop-blur-md shadow-lg shadow-emerald-900/20 flex items-center gap-3">
                                <div className="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></div>
                                {flash.status}
                            </div>
                        )}

                        {auth.user && unreadNotifications.length > 0 && (
                            <div className="mb-8 rounded-2xl border border-violet-500/20 bg-violet-500/10 p-5 backdrop-blur-md shadow-xl shadow-violet-900/20 relative overflow-hidden">
                                <div className="absolute top-0 right-0 p-10 bg-violet-500/20 blur-[50px] rounded-full"></div>
                                <div className="relative z-10">
                                    <div className="mb-3 flex items-center justify-between gap-3">
                                        <p className="flex items-center gap-2 font-bold text-violet-200 text-lg">
                                            <Bell size={20} className="text-violet-400" /> New updates from your favorite artists
                                        </p>
                                        <Link href="/profile" className="text-sm font-semibold text-violet-300 hover:text-white bg-white/5 border border-white/10 px-3 py-1.5 rounded-lg transition-all hover:bg-white/10">Open profile</Link>
                                    </div>
                                    <div className="space-y-2">
                                        {unreadNotifications.map((notification) => (
                                            <div key={notification.id} className="flex items-center gap-2 bg-black/20 rounded-lg px-4 py-2.5 text-slate-200 text-sm border border-white/5">
                                                <span className="h-1.5 w-1.5 rounded-full bg-violet-400"></span>
                                                <p className="flex-1">{notification.message}</p>
                                                {notification.action_url && (
                                                    <Link href={notification.action_url} className="text-violet-300 font-semibold hover:text-white transition-colors">
                                                        {notification.action_label || 'View'} &rarr;
                                                    </Link>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        )}

                        {children}
                    </main>
                </div>
            </div>
        </>
    );
}
