export default function EmptyState({ children }) {
    return (
        <div className="rounded-lg border border-dashed border-slate-700 bg-slate-900/40 p-6 text-sm text-slate-400">
            {children}
        </div>
    );
}
