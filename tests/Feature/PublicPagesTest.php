<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
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
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home', false)
                ->has('latestAlbums', 1)
                ->where('latestAlbums.0.title', $album->title)
                ->where('featuredArtists.0.name', $artist->name)
            );

        $this->get('/artists')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Artists/Index', false)
                ->where('artists.data.0.name', $artist->name)
            );

        $this->get("/artists/{$artist->id}")
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Artists/Show', false)
                ->where('artist.name', $artist->name)
                ->where('artist.spotify_url', 'https://open.spotify.com/artist/ocean-echo')
                ->where('artist.instagram_url', 'https://www.instagram.com/ocean.echo/')
                ->where('artist.albums.0.title', $album->title)
            );

        $this->get('/releases')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Albums/Index', false)
                ->where('albums.data.0.title', $album->title)
            );

        $this->get("/releases/{$album->id}")
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Albums/Show', false)
                ->where('album.title', $album->title)
                ->where('album.spotify_url', 'https://open.spotify.com/album/midnight-signal')
                ->where('album.artist.name', $artist->name)
            );
    }
}
