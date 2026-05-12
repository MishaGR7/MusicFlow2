@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
        <h1 class="text-2xl font-semibold text-white">Create account</h1>
        <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4">
            @csrf
            <input name="name" value="{{ old('name') }}" placeholder="Name" class="music-input w-full" minlength="2" required />
            <input name="email" type="email" value="{{ old('email') }}" placeholder="Email" class="music-input w-full" required />
            <input name="password" type="password" placeholder="Password" class="music-input w-full" required />
            <input name="password_confirmation" type="password" placeholder="Confirm password" class="music-input w-full" required />
            <input name="admin_code" type="password" placeholder="Admin code (optional)" class="music-input w-full" />
            <p class="text-xs text-slate-500">Вкажіть admin code, якщо хочете створити акаунт адміністратора.</p>
            @if($errors->any())
                <ul class="space-y-1 text-sm text-rose-400">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <button class="music-btn-primary w-full" type="submit">Register</button>
        </form>
    </div>
@endsection
