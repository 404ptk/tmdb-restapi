<?php

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
});
