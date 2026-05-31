import { useForm } from '@inertiajs/react';
import { LogIn } from 'lucide-react';
import Button from '../../Components/Button';
import { Checkbox, FieldError, Input } from '../../Components/Form';
import AppLayout from '../../Layouts/AppLayout';

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (event) => {
        event.preventDefault();
        post('/login');
    };

    return (
        <AppLayout title="Login">
            <div className="mx-auto max-w-md rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <h1 className="text-2xl font-semibold text-white">Login</h1>
                <form onSubmit={submit} className="mt-6 space-y-4">
                    <Input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} placeholder="Email" required error={errors.email} />
                    <Input type="password" value={data.password} onChange={(e) => setData('password', e.target.value)} placeholder="Password" required error={errors.password} />
                    <Checkbox label="Remember me" checked={data.remember} onChange={(e) => setData('remember', e.target.checked)} />
                    <FieldError message={errors.email} />
                    <Button type="submit" variant="primary" className="w-full" disabled={processing}>
                        <LogIn size={16} /> Sign in
                    </Button>
                </form>
            </div>
        </AppLayout>
    );
}
