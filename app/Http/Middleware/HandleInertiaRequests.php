<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'is_admin' => $user->isAdmin(),
                    'unread_notifications_count' => $user->unreadNotifications()->count(),
                ] : null,
            ],
            'flash' => [
                'status' => fn () => $request->session()->get('status'),
            ],
            'favoriteArtistIds' => fn () => $user
                ? $user->favoriteArtists()->pluck('artists.id')->all()
                : [],
            'unreadNotifications' => fn () => $user
                ? $user->unreadNotifications()
                    ->latest()
                    ->get()
                    ->map(fn ($notification) => [
                        'id' => $notification->id,
                        'message' => $notification->data['message'] ?? 'New album update.',
                        'action_url' => $notification->data['action_url'] ?? null,
                        'action_label' => $notification->data['action_label'] ?? 'Open',
                    ])
                : [],
        ];
    }
}
