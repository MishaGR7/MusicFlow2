import { Link } from '@inertiajs/react';

export default function Pagination({ links = [] }) {
    if (!links || links.length <= 3) {
        return null;
    }

    return (
        <nav className="mt-8 flex flex-wrap gap-2">
            {links.map((link, index) => (
                <Link
                    key={`${link.label}-${index}`}
                    href={link.url || '#'}
                    preserveScroll
                    className={`rounded-lg border px-3 py-2 text-sm transition ${
                        link.active
                            ? 'border-violet-500 bg-violet-600 text-white'
                            : 'border-slate-800 bg-slate-900 text-slate-300 hover:border-violet-500 hover:text-violet-200'
                    } ${!link.url ? 'pointer-events-none opacity-40' : ''}`}
                    dangerouslySetInnerHTML={{ __html: link.label }}
                />
            ))}
        </nav>
    );
}
