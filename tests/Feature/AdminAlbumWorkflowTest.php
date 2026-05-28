<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Artist;
use App\Models\User;
use App\Notifications\NewAlbumAdded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminAlbumWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_create_album_with_invalid_calendar_date(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $artist = Artist::create(['name' => 'The Signals']);

        $this->actingAs($admin)
            ->post('/admin/albums', [
                'artist_id' => $artist->id,
                'title' => 'Broken Date',
                'status' => 'published',
                'release_year' => 2026,
                'release_month' => 2,
                'release_day' => 31,
            ])
            ->assertSessionHasErrors(['release_day']);

        $this->assertDatabaseCount('albums', 0);
    }

    public function test_admin_can_create_album_and_notify_followers(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $listener = User::factory()->create();
        $artist = Artist::create(['name' => 'North Lights']);
        $artist->followers()->attach($listener->id);

        $this->actingAs($admin)
            ->post('/admin/albums', [
                'artist_id' => $artist->id,
                'title' => 'Northern Pulse',
                'status' => 'announced',
                'release_year' => 2027,
                'release_month' => 5,
            ])
            ->assertRedirect('/admin/albums');

        $album = Album::firstOrFail();

        $this->assertSame('2027-05', $album->release_date);

        Notification::assertSentTo($listener, NewAlbumAdded::class);
    }

    public function test_admin_can_add_tracks_and_select_title_track(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $artist = Artist::create(['name' => 'Neon Archive']);

        $this->actingAs($admin)
            ->post('/admin/albums', [
                'artist_id' => $artist->id,
                'title' => 'Electric Diary',
                'status' => 'published',
                'release_year' => 2026,
                'tracks' => [
                    ['title' => 'Intro Signal', 'duration' => '1:02'],
                    ['title' => 'Electric Diary', 'duration' => '3:44'],
                    ['title' => 'Afterglow', 'duration' => '2:58'],
                ],
                'title_track_index' => 1,
            ])
            ->assertRedirect('/admin/albums');

        $album = Album::with('tracks')->firstOrFail();

        $this->assertSame(3, $album->tracks->count());
        $this->assertSame('Electric Diary', $album->tracks->firstWhere('is_title_track', true)->title);
        $this->assertDatabaseHas('album_tracks', [
            'album_id' => $album->id,
            'position' => 2,
            'title' => 'Electric Diary',
            'is_title_track' => true,
        ]);
    }

    public function test_admin_must_select_title_track_when_tracks_are_added(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $artist = Artist::create(['name' => 'Clear Tone']);

        $this->actingAs($admin)
            ->post('/admin/albums', [
                'artist_id' => $artist->id,
                'title' => 'Missing Single',
                'status' => 'soon',
                'tracks' => [
                    ['title' => 'Untitled Track', 'duration' => '3:00'],
                ],
            ])
            ->assertSessionHasErrors(['title_track_index']);

        $this->assertDatabaseCount('albums', 0);
        $this->assertDatabaseCount('album_tracks', 0);
    }
}
