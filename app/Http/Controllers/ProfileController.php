<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        $user = $request->user()->load([
            'favoriteArtists' => fn ($query) => $query->with(['albums' => fn ($albumQuery) => $albumQuery->latest()])->orderBy('name'),
            'notifications' => fn ($query) => $query->latest(),
        ]);

        return Inertia::render('Profile/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'favorite_artists' => $user->favoriteArtists
                    ->map(fn (Artist $artist) => [
                        ...$this->artistData($artist),
                        'albums_count' => $artist->albums->count(),
                    ])
                    ->values(),
                'notifications' => $user->notifications
                    ->map(fn ($notification) => $this->notificationData($notification))
                    ->values(),
                'unread_notifications_count' => $user->unreadNotifications()->count(),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($request->user()->id),
            ],
        ]);

        $request->user()->update($validated);

        return back()->with('status', 'Profile updated.');
    }

    public function destroyFavorite(Request $request, Artist $artist): RedirectResponse
    {
        $request->user()->favoriteArtists()->detach($artist->id);

        return back()->with('status', 'Artist removed from favorites.');
    }

    public function markNotificationsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('status', 'Notifications marked as read.');
    }
}
