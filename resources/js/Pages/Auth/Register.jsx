import { useForm } from '@inertiajs/react';
import { UserPlus } from 'lucide-react';
import Button from '../../Components/Button';
import { Input } from '../../Components/Form';
import AppLayout from '../../Layouts/AppLayout';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        admin_code: '',
    });

    const submit = (event) => {
        event.preventDefault();
        post('/register');
    };

    return (
        <AppLayout title="Create account">
            <div className="mx-auto max-w-md rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <h1 className="text-2xl font-semibold text-white">Create account</h1>
                <form onSubmit={submit} className="mt-6 space-y-4">
                    <Input value={data.name} onChange={(e) => setData('name', e.target.value)} placeholder="Name" minLength="2" required error={errors.name} />
                    <Input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} placeholder="Email" required error={errors.email} />
                    <Input type="password" value={data.password} onChange={(e) => setData('password', e.target.value)} placeholder="Password" required error={errors.password} />
                    <Input type="password" value={data.password_confirmation} onChange={(e) => setData('password_confirmation', e.target.value)} placeholder="Confirm password" required error={errors.password_confirmation} />
                    <Input type="password" value={data.admin_code} onChange={(e) => setData('admin_code', e.target.value)} placeholder="Admin code (optional)" error={errors.admin_code} />
                    <p className="text-xs text-slate-500">Вкажіть admin code, якщо хочете створити акаунт адміністратора.</p>
                    <Button type="submit" variant="primary" className="w-full" disabled={processing}>
                        <UserPlus size={16} /> Register
                    </Button>
                </form>
            </div>
        </AppLayout>
    );
}
