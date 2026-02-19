<?php

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('tmdb', [
        'movieCount' => Movie::count(),
        'seriesCount' => Serie::count(),
        'genreCount' => Genre::count(),
        'movies' => Movie::orderByDesc('popularity')->limit(8)->get(),
        'series' => Serie::orderByDesc('popularity')->limit(6)->get(),
        'genres' => Genre::orderBy('name_en')->get(),
    ]);
});
