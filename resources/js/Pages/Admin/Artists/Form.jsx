import { Save } from 'lucide-react';
import Button from '../../../Components/Button';
import { Input, Select, Textarea } from '../../../Components/Form';

export default function ArtistForm({ data, setData, errors, processing, submitLabel = 'Save artist' }) {
    const types = {
        solo: 'Solo',
        group: 'Group',
        band: 'Band',
        duo: 'Duo',
        project: 'Project',
    };

    return (
        <div>
            <div className="grid gap-4 md:grid-cols-2">
                <Input value={data.name} onChange={(e) => setData('name', e.target.value)} placeholder="Artist name" minLength="2" required error={errors.name} />
                <Input value={data.country} onChange={(e) => setData('country', e.target.value)} placeholder="Country" error={errors.country} />
                <Input type="date" value={data.debut_date} onChange={(e) => setData('debut_date', e.target.value)} error={errors.debut_date} />
                <Input value={data.company} onChange={(e) => setData('company', e.target.value)} placeholder="Company / agency" error={errors.company} />
                <Select value={data.artist_type} onChange={(e) => setData('artist_type', e.target.value)} required error={errors.artist_type}>
                    {Object.entries(types).map(([value, label]) => <option key={value} value={value}>{label}</option>)}
                </Select>
                <Input type="number" value={data.members_count} onChange={(e) => setData('members_count', e.target.value)} placeholder="Members count" min="1" max="200" error={errors.members_count} />
                <Input value={data.fandom_name} onChange={(e) => setData('fandom_name', e.target.value)} placeholder="Fandom name" error={errors.fandom_name} />
                <Input type="url" value={data.official_site} onChange={(e) => setData('official_site', e.target.value)} placeholder="Official site https://..." error={errors.official_site} />
                <Input type="url" value={data.spotify_url} onChange={(e) => setData('spotify_url', e.target.value)} placeholder="Spotify artist URL https://open.spotify.com/artist/..." error={errors.spotify_url} />
                <Input type="url" value={data.instagram_url} onChange={(e) => setData('instagram_url', e.target.value)} placeholder="Instagram URL https://www.instagram.com/..." error={errors.instagram_url} />
                <Textarea className="md:col-span-2" rows="8" value={data.bio} onChange={(e) => setData('bio', e.target.value)} placeholder="Detailed biography, history, achievements, concept, notes" error={errors.bio} />
                <Input className="md:col-span-2" type="file" accept="image/*" onChange={(e) => setData('photo', e.target.files[0])} error={errors.photo} />
            </div>
            <Button className="mt-4" type="submit" variant="primary" disabled={processing}>
                <Save size={16} /> {submitLabel}
            </Button>
        </div>
    );
}
