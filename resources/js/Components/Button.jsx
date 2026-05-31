import { Link } from '@inertiajs/react';

const variants = {
    primary: 'music-btn-primary',
    secondary: 'music-btn-secondary',
    danger: 'music-btn-danger',
};

export default function Button({ href, method, as, variant = 'secondary', className = '', children, ...props }) {
    const classes = `${variants[variant] ?? variants.secondary} ${className}`.trim();

    if (href) {
        if (/^https?:\/\//.test(href)) {
            return (
                <a href={href} className={classes} target="_blank" rel="noopener noreferrer" {...props}>
                    {children}
                </a>
            );
        }

        return (
            <Link href={href} method={method} as={as} className={classes} {...props}>
                {children}
            </Link>
        );
    }

    return (
        <button className={classes} {...props}>
            {children}
        </button>
    );
}
