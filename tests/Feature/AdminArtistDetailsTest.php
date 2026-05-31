<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminArtistDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_artist_catalog_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post('/admin/artists', [
                'name' => 'Lunar Route',
                'country' => 'South Korea',
                'bio' => 'A detailed artist biography.',
                'debut_date' => '2022-08-14',
                'company' => 'Orbit Studio',
                'artist_type' => 'group',
                'members_count' => 5,
                'fandom_name' => 'Travelers',
                'official_site' => 'https://example.com/lunar-route',
                'spotify_url' => 'https://open.spotify.com/artist/example',
                'instagram_url' => 'https://www.instagram.com/lunar.route/',
            ])
            ->assertRedirect('/admin/artists');

        $this->assertDatabaseHas('artists', [
            'name' => 'Lunar Route',
            'company' => 'Orbit Studio',
            'artist_type' => 'group',
            'members_count' => 5,
            'fandom_name' => 'Travelers',
            'spotify_url' => 'https://open.spotify.com/artist/example',
            'instagram_url' => 'https://www.instagram.com/lunar.route/',
        ]);
    }

    public function test_public_artist_catalog_filters_by_company_and_type(): void
    {
        Artist::create([
            'name' => 'Visible Group',
            'company' => 'Signal House',
            'artist_type' => 'group',
        ]);

        Artist::create([
            'name' => 'Hidden Soloist',
            'company' => 'Other Company',
            'artist_type' => 'solo',
        ]);

        $this->get('/artists?company=Signal+House&type=group')
            ->assertOk()
            ->assertSee('Visible Group')
            ->assertDontSee('Hidden Soloist');
    }
}
