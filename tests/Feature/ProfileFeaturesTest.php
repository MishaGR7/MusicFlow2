<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Artist;
use App\Models\User;
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
            'status' => 'soon',
        ]);

        Album::create([
            'artist_id' => $otherArtist->id,
            'title' => 'Hidden Release',
            'status' => 'soon',
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
}
