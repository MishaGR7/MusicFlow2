@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
        <h1 class="text-2xl font-semibold text-white">Login</h1>
        <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">
            @csrf
            <input name="email" type="email" value="{{ old('email') }}" placeholder="Email" class="music-input w-full" required />
            <input name="password" type="password" placeholder="Password" class="music-input w-full" required />
            <label class="flex items-center gap-2 text-sm text-slate-300">
                <input type="checkbox" name="remember" />
                Remember me
            </label>
            @error('email') <p class="text-sm text-rose-400">{{ $message }}</p> @enderror
            <button class="music-btn-primary w-full" type="submit">Sign in</button>
        </form>
    </div>
@endsection
