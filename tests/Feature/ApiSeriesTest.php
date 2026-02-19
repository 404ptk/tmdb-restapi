<?php

namespace Tests\Feature;

use App\Models\Serie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiSeriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_series_endpoint_respects_accept_language(): void
    {
        Serie::create([
            'tmdb_id' => 1,
            'title_en' => 'English Series',
            'title_pl' => 'Polski Serial',
            'title_de' => 'Deutsche Serie',
            'overview_en' => 'English overview',
            'overview_pl' => 'Polski opis',
            'overview_de' => 'Deutsche Beschreibung',
            'first_air_date' => '2026-01-01',
            'poster_path' => '/poster.jpg',
            'popularity' => 10.5,
            'vote_average' => 7.1,
            'vote_count' => 100,
        ]);

        $response = $this->getJson('/api/series', [
            'Accept-Language' => 'de',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Deutsche Serie');
    }

    public function test_series_endpoint_paginates(): void
    {
        foreach (range(1, 12) as $index) {
            Serie::create([
                'tmdb_id' => $index,
                'title_en' => "Series {$index}",
                'title_pl' => "Serial {$index}",
                'title_de' => "Serie {$index}",
                'overview_en' => "Overview {$index}",
                'overview_pl' => "Opis {$index}",
                'overview_de' => "Beschreibung {$index}",
                'first_air_date' => '2026-01-01',
                'poster_path' => '/poster.jpg',
                'popularity' => $index,
                'vote_average' => 6.5,
                'vote_count' => 10,
            ]);
        }

        $response = $this->getJson('/api/series?per_page=5');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('per_page', 5);
    }
}
