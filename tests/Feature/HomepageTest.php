<?php

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_livewire_movies_table(): void
    {
        Movie::create([
            'tmdb_id' => 1,
            'title_en' => 'Movie 1',
            'title_pl' => 'Film 1',
            'title_de' => 'Film 1',
            'overview_en' => 'Overview 1',
            'overview_pl' => 'Opis 1',
            'overview_de' => 'Beschreibung 1',
            'release_date' => '2026-01-01',
            'poster_path' => '/poster.jpg',
            'popularity' => 1,
            'vote_average' => 6.5,
            'vote_count' => 10,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Movies List')
            ->assertSee('Movie 1');
    }
}
