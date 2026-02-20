<?php

namespace App\Jobs;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use App\Services\TmdbClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ImportTmdbData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const LANGUAGES = [
        'en' => 'en-US',
        'pl' => 'pl-PL',
        'de' => 'de-DE',
    ];

    public function handle(TmdbClient $client): void
    {
        if (! $client->isConfigured()) {
            Log::warning('TMDB API key not configured.');
            return;
        }

        Log::info('Starting TMDB data import...');

        Log::info('Fetching genres...');
        $genres = $this->fetchGenres($client);
        $this->storeGenres($genres);
        Log::info('Imported ' . count($genres) . ' genres');

        Log::info('Fetching movies...');
        $movies = $this->fetchMovies($client);
        $this->storeMovies($movies);
        Log::info('Imported ' . count($movies) . ' movies');

        Log::info('Fetching TV series...');
        $series = $this->fetchSeries($client);
        $this->storeSeries($series);
        Log::info('Imported ' . count($series) . ' series');

        Log::info('TMDB import completed successfully!');
    }

    private function fetchGenres(TmdbClient $client): array
    {
        $genreMap = [];

        foreach (self::LANGUAGES as $suffix => $language) {
            $movieGenres = $client->get('/genre/movie/list', ['language' => $language]);
            $tvGenres = $client->get('/genre/tv/list', ['language' => $language]);

            $all = array_merge($movieGenres['genres'] ?? [], $tvGenres['genres'] ?? []);

            foreach ($all as $genre) {
                $id = Arr::get($genre, 'id');
                if (! $id) {
                    continue;
                }

                $genreMap[$id] ??= [
                    'tmdb_id' => $id,
                    'name_en' => null,
                    'name_pl' => null,
                    'name_de' => null,
                ];

                $genreMap[$id]['name_' . $suffix] = Arr::get($genre, 'name');
            }
        }

        return array_values($genreMap);
    }

    private function fetchMovies(TmdbClient $client): array
    {
        $movieMap = [];
        $moviesEn = [];

        foreach (self::LANGUAGES as $suffix => $language) {
            $results = $this->discover($client, '/discover/movie', $language, 3);

            if ($suffix === 'en') {
                $moviesEn = $results;
            }

            foreach ($results as $movie) {
                $id = Arr::get($movie, 'id');
                if (! $id) {
                    continue;
                }

                $movieMap[$id] ??= [
                    'tmdb_id' => $id,
                    'title_en' => null,
                    'title_pl' => null,
                    'title_de' => null,
                    'overview_en' => null,
                    'overview_pl' => null,
                    'overview_de' => null,
                    'release_date' => null,
                    'poster_path' => null,
                    'popularity' => null,
                    'vote_average' => null,
                    'vote_count' => 0,
                ];

                $movieMap[$id]['title_' . $suffix] = Arr::get($movie, 'title');
                $movieMap[$id]['overview_' . $suffix] = Arr::get($movie, 'overview');

                if ($suffix === 'en') {
                    $movieMap[$id]['release_date'] = Arr::get($movie, 'release_date');
                    $movieMap[$id]['poster_path'] = Arr::get($movie, 'poster_path');
                    $movieMap[$id]['popularity'] = Arr::get($movie, 'popularity');
                    $movieMap[$id]['vote_average'] = Arr::get($movie, 'vote_average');
                    $movieMap[$id]['vote_count'] = Arr::get($movie, 'vote_count', 0);
                }
            }
        }

        $topMovies = array_slice($moviesEn, 0, 50);

        return array_values(array_intersect_key($movieMap, array_flip(array_column($topMovies, 'id'))));
    }

    private function fetchSeries(TmdbClient $client): array
    {
        $seriesMap = [];
        $seriesEn = [];

        foreach (self::LANGUAGES as $suffix => $language) {
            $results = $this->discover($client, '/discover/tv', $language, 1);

            if ($suffix === 'en') {
                $seriesEn = $results;
            }

            foreach ($results as $serie) {
                $id = Arr::get($serie, 'id');
                if (! $id) {
                    continue;
                }

                $seriesMap[$id] ??= [
                    'tmdb_id' => $id,
                    'title_en' => null,
                    'title_pl' => null,
                    'title_de' => null,
                    'overview_en' => null,
                    'overview_pl' => null,
                    'overview_de' => null,
                    'first_air_date' => null,
                    'poster_path' => null,
                    'popularity' => null,
                    'vote_average' => null,
                    'vote_count' => 0,
                ];

                $seriesMap[$id]['title_' . $suffix] = Arr::get($serie, 'name');
                $seriesMap[$id]['overview_' . $suffix] = Arr::get($serie, 'overview');

                if ($suffix === 'en') {
                    $seriesMap[$id]['first_air_date'] = Arr::get($serie, 'first_air_date');
                    $seriesMap[$id]['poster_path'] = Arr::get($serie, 'poster_path');
                    $seriesMap[$id]['popularity'] = Arr::get($serie, 'popularity');
                    $seriesMap[$id]['vote_average'] = Arr::get($serie, 'vote_average');
                    $seriesMap[$id]['vote_count'] = Arr::get($serie, 'vote_count', 0);
                }
            }
        }

        $topSeries = array_slice($seriesEn, 0, 10);

        return array_values(array_intersect_key($seriesMap, array_flip(array_column($topSeries, 'id'))));
    }

    private function discover(TmdbClient $client, string $path, string $language, int $pages): array
    {
        $results = [];

        for ($page = 1; $page <= $pages; $page++) {
            $response = $client->get($path, [
                'language' => $language,
                'page' => $page,
                'sort_by' => 'popularity.desc',
                'include_adult' => false,
            ]);

            $results = array_merge($results, $response['results'] ?? []);
        }

        return $results;
    }

    private function storeGenres(array $genres): void
    {
        $count = 0;
        foreach ($genres as $genre) {
            Genre::updateOrCreate(
                ['tmdb_id' => $genre['tmdb_id']],
                $genre
            );
            $count++;
            if ($count % 5 === 0) {
                Log::info("  Stored {$count}/{" . count($genres) . "} genres");
            }
        }
    }

    private function storeMovies(array $movies): void
    {
        $count = 0;
        foreach ($movies as $movie) {
            Movie::updateOrCreate(
                ['tmdb_id' => $movie['tmdb_id']],
                $movie
            );
            $count++;
            if ($count % 10 === 0) {
                Log::info("  Stored {$count}/{" . count($movies) . "} movies");
            }
        }
    }

    private function storeSeries(array $series): void
    {
        $count = 0;
        foreach ($series as $serie) {
            Serie::updateOrCreate(
                ['tmdb_id' => $serie['tmdb_id']],
                $serie
            );
            $count++;
            if ($count % 5 === 0) {
                Log::info("  Stored {$count}/{" . count($series) . "} series");
            }
        }
    }
}
