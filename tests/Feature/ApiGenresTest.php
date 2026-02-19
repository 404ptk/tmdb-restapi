<?php

namespace Tests\Feature;

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiGenresTest extends TestCase
{
    use RefreshDatabase;

    public function test_genres_endpoint_respects_accept_language(): void
    {
        Genre::create([
            'tmdb_id' => 1,
            'name_en' => 'Action',
            'name_pl' => 'Akcja',
            'name_de' => 'Aktion',
        ]);

        $response = $this->getJson('/api/genres', [
            'Accept-Language' => 'pl',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Akcja');
    }

    public function test_genres_endpoint_returns_list(): void
    {
        foreach (range(1, 3) as $index) {
            Genre::create([
                'tmdb_id' => $index,
                'name_en' => "Genre {$index}",
                'name_pl' => "Gatunek {$index}",
                'name_de' => "Genre {$index}",
            ]);
        }

        $response = $this->getJson('/api/genres');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_genres_endpoint_paginates_when_requested(): void
    {
        foreach (range(1, 12) as $index) {
            Genre::create([
                'tmdb_id' => $index,
                'name_en' => "Genre {$index}",
                'name_pl' => "Gatunek {$index}",
                'name_de' => "Genre {$index}",
            ]);
        }

        $response = $this->getJson('/api/genres?per_page=5');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('per_page', 5);
    }
}
