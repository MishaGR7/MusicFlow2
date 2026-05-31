import { ChevronDown } from 'lucide-react';
import { Children, isValidElement, useEffect, useMemo, useRef, useState } from 'react';

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

export function Select({ error, className = '', children, value = '', onChange, ...props }) {
    const [open, setOpen] = useState(false);
    const rootRef = useRef(null);
    const options = useMemo(() => Children.toArray(children)
        .filter(isValidElement)
        .map((child) => ({
            value: child.props.value ?? '',
            label: child.props.children,
            disabled: child.props.disabled ?? false,
        })), [children]);
    const selected = options.find((option) => String(option.value) === String(value)) ?? options[0];

    useEffect(() => {
        const closeOnOutsideClick = (event) => {
            if (rootRef.current && !rootRef.current.contains(event.target)) {
                setOpen(false);
            }
        };

        document.addEventListener('mousedown', closeOnOutsideClick);

        return () => document.removeEventListener('mousedown', closeOnOutsideClick);
    }, []);

    const choose = (optionValue) => {
        onChange?.({ target: { value: optionValue } });
        setOpen(false);
    };

    return (
        <div className={`relative ${className}`} ref={rootRef}>
            <button
                type="button"
                className="music-input flex w-full items-center justify-between gap-3 text-left"
                onClick={() => setOpen((current) => !current)}
                aria-expanded={open}
                {...props}
            >
                <span className="truncate">{selected?.label}</span>
                <ChevronDown size={16} className={`shrink-0 text-slate-400 transition ${open ? 'rotate-180' : ''}`} />
            </button>
            {open && (
                <div className="absolute left-0 right-0 top-[calc(100%+0.375rem)] z-50 max-h-64 overflow-y-auto rounded-lg border border-slate-700 bg-slate-950 py-1 shadow-xl shadow-black/40">
                    {options.map((option) => (
                        <button
                            key={`${option.value}-${option.label}`}
                            type="button"
                            disabled={option.disabled}
                            onClick={() => choose(option.value)}
                            className={`block w-full px-3 py-2 text-left text-sm transition ${
                                String(option.value) === String(value)
                                    ? 'bg-violet-600 text-white'
                                    : 'text-slate-200 hover:bg-slate-800 hover:text-violet-100'
                            } disabled:cursor-not-allowed disabled:opacity-50`}
                        >
                            {option.label}
                        </button>
                    ))}
                </div>
            )}
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
