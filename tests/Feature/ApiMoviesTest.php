<?php

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiMoviesTest extends TestCase
{
    use RefreshDatabase;

    public function test_movies_endpoint_respects_accept_language(): void
    {
        Movie::create([
            'tmdb_id' => 1,
            'title_en' => 'English Title',
            'title_pl' => 'Polski Tytul',
            'title_de' => 'Deutscher Titel',
            'overview_en' => 'English overview',
            'overview_pl' => 'Polski opis',
            'overview_de' => 'Deutsche Beschreibung',
            'release_date' => '2026-01-01',
            'poster_path' => '/poster.jpg',
            'popularity' => 10.5,
            'vote_average' => 7.1,
            'vote_count' => 100,
        ]);

        $response = $this->getJson('/api/movies', [
            'Accept-Language' => 'pl',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Polski Tytul');

        $response = $this->getJson('/api/movies', [
            'Accept-Language' => 'en',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'English Title');

        $response = $this->getJson('/api/movies', [
            'Accept-Language' => 'de',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Deutscher Titel');
    }

    public function test_movies_endpoint_paginates(): void
    {
        foreach (range(1, 15) as $index) {
            Movie::create([
                'tmdb_id' => $index,
                'title_en' => "Movie {$index}",
                'title_pl' => "Film {$index}",
                'title_de' => "Film {$index}",
                'overview_en' => "Overview {$index}",
                'overview_pl' => "Opis {$index}",
                'overview_de' => "Beschreibung {$index}",
                'release_date' => '2026-01-01',
                'poster_path' => '/poster.jpg',
                'popularity' => $index,
                'vote_average' => 6.5,
                'vote_count' => 10,
            ]);
        }

        $response = $this->getJson('/api/movies?per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('per_page', 10);
    }
}
