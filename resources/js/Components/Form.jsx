export function FieldError({ message }) {
    if (!message) {
        return null;
    }

    return <p className="mt-1 text-sm text-rose-400">{message}</p>;
}

export function Input({ error, className = '', ...props }) {
    return (
        <div className={className}>
            <input className="music-input w-full" {...props} />
            <FieldError message={error} />
        </div>
    );
}

export function Textarea({ error, className = '', ...props }) {
    return (
        <div className={className}>
            <textarea className="music-input w-full" {...props} />
            <FieldError message={error} />
        </div>
    );
}

export function Select({ error, className = '', children, ...props }) {
    return (
        <div className={className}>
            <select className="music-input w-full" {...props}>
                {children}
            </select>
            <FieldError message={error} />
        </div>
    );
}

export function Checkbox({ label, className = '', ...props }) {
    return (
        <label className={`flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-200 ${className}`}>
            <input type="checkbox" className="accent-violet-500" {...props} />
            {label}
        </label>
    );
}
