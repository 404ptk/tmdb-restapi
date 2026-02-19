<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TMDB Dashboard</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-slate-950 text-slate-100">
        <div class="min-h-screen">
            <header class="border-b border-slate-800 bg-slate-900/60 backdrop-blur">
                <div class="mx-auto max-w-6xl px-6 py-6">
                    <div class="flex flex-col gap-2">
                        <h1 class="text-2xl font-semibold">TMDB – Simple data overview</h1>
                        <p class="text-sm text-slate-300">Data from the database after TMDB import.</p>
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-6xl px-6 py-8 space-y-10">
                <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
                        <p class="text-sm text-slate-400">Movies</p>
                        <p class="mt-2 text-3xl font-semibold">{{ $movieCount }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
                        <p class="text-sm text-slate-400">Series</p>
                        <p class="mt-2 text-3xl font-semibold">{{ $seriesCount }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
                        <p class="text-sm text-slate-400">Genres</p>
                        <p class="mt-2 text-3xl font-semibold">{{ $genreCount }}</p>
                    </div>
                </section>

                <section class="rounded-xl border border-slate-800 bg-slate-900 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold">Top movies</h2>
                        <span class="text-xs text-slate-400">Showing up to 8</span>
                    </div>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        @forelse ($movies as $movie)
                            <div class="rounded-lg border border-slate-800 bg-slate-950 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-medium">{{ $movie->title_en }}</p>
                                        <p class="text-xs text-slate-400">{{ $movie->release_date?->format('Y-m-d') ?? 'no date' }}</p>
                                    </div>
                                    <span class="text-xs text-slate-400">★ {{ number_format((float) $movie->vote_average, 1) }}</span>
                                </div>
                                @if ($movie->overview_en)
                                    <p class="mt-3 text-sm text-slate-300 line-clamp-3">{{ $movie->overview_en }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">No movies in the database.</p>
                        @endforelse
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
                        <h2 class="text-lg font-semibold">Latest series</h2>
                        <div class="mt-4 space-y-3">
                            @forelse ($series as $serie)
                                <div class="flex items-center justify-between rounded-lg border border-slate-800 bg-slate-950 p-3">
                                    <div>
                                        <p class="font-medium">{{ $serie->title_en }}</p>
                                        <p class="text-xs text-slate-400">{{ $serie->first_air_date?->format('Y-m-d') ?? 'no date' }}</p>
                                    </div>
                                    <span class="text-xs text-slate-400">★ {{ number_format((float) $serie->vote_average, 1) }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-slate-400">No series in the database.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
                        <h2 class="text-lg font-semibold">Genres</h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @forelse ($genres as $genre)
                                <span class="rounded-full border border-slate-700 bg-slate-950 px-3 py-1 text-xs text-slate-200">
                                    {{ $genre->name_en }}
                                </span>
                            @empty
                                <p class="text-sm text-slate-400">No genres in the database.</p>
                            @endforelse
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
