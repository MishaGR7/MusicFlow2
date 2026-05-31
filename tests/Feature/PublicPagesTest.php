<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_music_content(): void
    {
        $artist = Artist::create([
            'name' => 'Ocean Echo',
            'country' => 'Ukraine',
            'bio' => 'Indie project for testing.',
            'spotify_url' => 'https://open.spotify.com/artist/ocean-echo',
            'instagram_url' => 'https://www.instagram.com/ocean.echo/',
        ]);

        $album = Album::create([
            'artist_id' => $artist->id,
            'title' => 'Midnight Signal',
            'release_date' => '2026-05',
            'status' => 'announced',
            'spotify_url' => 'https://open.spotify.com/album/midnight-signal',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('MusicFlow')
            ->assertSee($album->title)
            ->assertSee($artist->name);

        $this->get('/artists')
            ->assertOk()
            ->assertSee($artist->name);

        $this->get("/artists/{$artist->id}")
            ->assertOk()
            ->assertSee($artist->name)
            ->assertSee('Spotify')
            ->assertSee('Instagram')
            ->assertSee($album->title);

        $this->get('/releases')
            ->assertOk()
            ->assertSee($album->title);

        $this->get("/releases/{$album->id}")
            ->assertOk()
            ->assertSee($album->title)
            ->assertSee('Open on Spotify')
            ->assertSee($artist->name);
    }
}
