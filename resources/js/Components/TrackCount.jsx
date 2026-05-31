export function getTrackCountLabel(count) {
    if (count <= 1) {
        return null;
    }

    return `${count} tracks`;
}

export default function TrackCount({ count, className = '' }) {
    const label = getTrackCountLabel(count);

    if (!label) {
        return null;
    }

    return <p className={className}>{label}</p>;
}
