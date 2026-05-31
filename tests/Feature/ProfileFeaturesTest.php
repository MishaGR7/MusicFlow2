<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Artist;
use App\Models\User;
use App\Notifications\AlbumReleasedToday;
use App\Notifications\NewAlbumAdded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_updates_user_data_and_removes_favorites(): void
    {
        $user = User::factory()->create();
        $artist = Artist::create(['name' => 'Solar Harbor']);
        $user->favoriteArtists()->attach($artist->id);

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Updated Listener',
                'email' => 'listener@example.com',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Listener',
            'email' => 'listener@example.com',
        ]);

        $this->actingAs($user)
            ->delete("/profile/favorites/{$artist->id}")
            ->assertRedirect();

        $this->assertDatabaseCount('artist_user', 0);
    }

    public function test_release_filter_can_show_only_favorite_artists_and_notifications_can_be_marked_read(): void
    {
        $user = User::factory()->create();
        $favoriteArtist = Artist::create(['name' => 'Favorite Artist']);
        $otherArtist = Artist::create(['name' => 'Other Artist']);

        $favoriteAlbum = Album::create([
            'artist_id' => $favoriteArtist->id,
            'title' => 'Favorite Release',
            'status' => 'tba',
        ]);

        Album::create([
            'artist_id' => $otherArtist->id,
            'title' => 'Hidden Release',
            'status' => 'tba',
        ]);

        $user->favoriteArtists()->attach($favoriteArtist->id);
        $user->notify(new NewAlbumAdded($favoriteAlbum));

        $this->actingAs($user)
            ->get('/releases?favorites=1')
            ->assertOk()
            ->assertSee('Favorite Release')
            ->assertDontSee('Hidden Release');

        $this->actingAs($user)
            ->post('/profile/notifications/read')
            ->assertRedirect();

        $this->assertSame(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_release_day_notifications_are_sent_once_for_favorite_artist_albums(): void
    {
        $user = User::factory()->create();
        $favoriteArtist = Artist::create(['name' => 'Release Day Artist']);
        $otherArtist = Artist::create(['name' => 'Quiet Artist']);

        $todayAlbum = Album::create([
            'artist_id' => $favoriteArtist->id,
            'title' => 'Today Signal',
            'release_date' => '2026-05-29',
            'status' => 'published',
        ]);

        Album::create([
            'artist_id' => $favoriteArtist->id,
            'title' => 'Month Only',
            'release_date' => '2026-05',
            'status' => 'announced',
        ]);

        Album::create([
            'artist_id' => $otherArtist->id,
            'title' => 'Other Today',
            'release_date' => '2026-05-29',
            'status' => 'published',
        ]);

        $user->favoriteArtists()->attach($favoriteArtist->id);

        $this->artisan('musicflow:send-release-day-notifications', ['--date' => '2026-05-29'])
            ->expectsOutput('Sent 1 due release notifications through 2026-05-29.')
            ->assertSuccessful();

        $this->artisan('musicflow:send-release-day-notifications', ['--date' => '2026-05-29'])
            ->expectsOutput('Sent 0 due release notifications through 2026-05-29.')
            ->assertSuccessful();

        $notification = $user->notifications()->firstOrFail();

        $this->assertSame(AlbumReleasedToday::class, $notification->type);
        $this->assertSame($todayAlbum->id, $notification->data['album_id']);
        $this->assertSame('Released today: Release Day Artist - Today Signal', $notification->data['message']);

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk()
            ->assertSee('Released today: Release Day Artist - Today Signal')
            ->assertSee('Open release');
    }

    public function test_release_day_notifications_catch_up_after_release_date(): void
    {
        $user = User::factory()->create();
        $artist = Artist::create(['name' => 'Late Listener Artist']);

        $album = Album::create([
            'artist_id' => $artist->id,
            'title' => 'Already Out',
            'release_date' => '2026-05-20',
            'status' => 'published',
        ]);

        $user->favoriteArtists()->attach($artist->id);

        $this->artisan('musicflow:send-release-day-notifications', ['--date' => '2026-05-29'])
            ->expectsOutput('Sent 1 due release notifications through 2026-05-29.')
            ->assertSuccessful();

        $notification = $user->notifications()->firstOrFail();

        $this->assertSame($album->id, $notification->data['album_id']);
        $this->assertSame('Release available: Late Listener Artist - Already Out', $notification->data['message']);
        $this->assertSame('2026-05-20', $notification->data['release_date']);

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk()
            ->assertSee('Release available: Late Listener Artist - Already Out');
    }
}
