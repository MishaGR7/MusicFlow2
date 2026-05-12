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
        ]);

        $album = Album::create([
            'artist_id' => $artist->id,
            'title' => 'Midnight Signal',
            'release_date' => '2026-05',
            'status' => 'announced',
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
            ->assertSee($album->title);

        $this->get('/releases')
            ->assertOk()
            ->assertSee($album->title);

        $this->get("/releases/{$album->id}")
            ->assertOk()
            ->assertSee($album->title)
            ->assertSee($artist->name);
    }
}
