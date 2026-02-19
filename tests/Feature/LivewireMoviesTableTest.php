<?php

namespace Tests\Feature;

use App\Http\Livewire\MoviesTable;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireMoviesTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_movies_table_renders_and_paginates(): void
    {
        foreach (range(1, 12) as $index) {
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

        Livewire::test(MoviesTable::class)
            ->assertSee('Movie 12')
            ->assertSee('Showing 1 to 10 of 12')
            ->assertSee('Page 1 of 2');
    }
}
