import { Link, router, useForm } from '@inertiajs/react';
import { CheckCheck, Trash2 } from 'lucide-react';
import Button from '../../Components/Button';
import EmptyState from '../../Components/EmptyState';
import { Input } from '../../Components/Form';
import AppLayout from '../../Layouts/AppLayout';

export default function Edit({ user }) {
    const { data, setData, patch, processing, errors } = useForm({
        name: user.name || '',
        email: user.email || '',
    });

    const submit = (event) => {
        event.preventDefault();
        patch('/profile');
    };

    return (
        <AppLayout title="Profile & Settings">
            <div className="grid gap-8 xl:grid-cols-[minmax(0,1fr)_380px]">
                <section className="rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                    <h1 className="text-3xl font-bold text-white">Profile & Settings</h1>
                    <p className="mt-2 text-sm text-slate-400">Manage your identity, review favorite artists, and keep notifications tidy.</p>
                    <form onSubmit={submit} className="mt-6 grid gap-4 md:grid-cols-2">
                        <Input value={data.name} onChange={(e) => setData('name', e.target.value)} placeholder="Name" minLength="2" required error={errors.name} />
                        <Input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} placeholder="Email" required error={errors.email} />
                        <div className="md:col-span-2">
                            <Button type="submit" variant="primary" disabled={processing}>Save profile</Button>
                        </div>
                    </form>
                    <div className="mt-10">
                        <h2 className="text-2xl font-bold text-white">Favorite Artists</h2>
                        <p className="text-sm text-slate-400">Quick unsubscribe controls live here.</p>
                        <div className="mt-4 grid gap-4">
                            {user.favorite_artists.length ? user.favorite_artists.map((artist) => (
                                <article key={artist.id} className="rounded-lg border border-slate-800 bg-slate-950/80 p-4">
                                    <div className="flex flex-wrap items-start justify-between gap-4">
                                        <div>
                                            <h3 className="text-lg font-semibold text-white">{artist.name}</h3>
                                            <p className="text-sm text-slate-400">{artist.country || 'Unknown country'}</p>
                                            <p className="mt-2 text-sm text-slate-300">{artist.albums_count} releases available</p>
                                        </div>
                                        <Button variant="danger" type="button" onClick={() => router.delete(`/profile/favorites/${artist.id}`, { preserveScroll: true })}>
                                            <Trash2 size={16} /> Remove
                                        </Button>
                                    </div>
                                </article>
                            )) : <EmptyState>You are not following any artists yet.</EmptyState>}
                        </div>
                    </div>
                </section>
                <aside className="rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                    <div className="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h2 className="text-2xl font-bold text-white">Notifications</h2>
                            <p className="text-sm text-slate-400">Announcements from your favorite artists.</p>
                        </div>
                        {user.unread_notifications_count > 0 && (
                            <Button type="button" onClick={() => router.post('/profile/notifications/read', {}, { preserveScroll: true })}>
                                <CheckCheck size={16} /> Mark all read
                            </Button>
                        )}
                    </div>
                    <div className="space-y-3">
                        {user.notifications.length ? user.notifications.map((notification) => (
                            <article key={notification.id} className={`rounded-lg border px-4 py-3 text-sm ${notification.read_at ? 'border-slate-800 bg-slate-950/70 text-slate-400' : 'border-violet-800 bg-violet-950/40 text-violet-100'}`}>
                                <div className="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <p className="font-medium">{notification.message}</p>
                                        <p className={`mt-2 text-xs ${notification.read_at ? 'text-slate-500' : 'text-violet-300'}`}>{notification.created_at}</p>
                                    </div>
                                    {notification.action_url && <Link href={notification.action_url} className="text-xs font-semibold text-violet-300 hover:text-violet-200">{notification.action_label || 'Open'}</Link>}
                                </div>
                            </article>
                        )) : <EmptyState>No notifications yet.</EmptyState>}
                    </div>
                </aside>
            </div>
        </AppLayout>
    );
}
