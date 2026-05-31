const statusClasses = {
    published: 'music-badge-published',
    announced: 'music-badge-announced',
    tba: 'music-badge-tba',
};

export default function Badge({ status, children }) {
    return (
        <span className={`music-badge ${statusClasses[status] ?? 'bg-slate-800 text-slate-300'}`}>
            {children ?? String(status || '').toUpperCase()}
        </span>
    );
}
