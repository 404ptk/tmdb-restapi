<?php

use Illuminate\Support\Facades\Route;

Route::get('/movies', [\App\Http\Controllers\Api\MovieController::class, 'index']);
Route::get('/series', [\App\Http\Controllers\Api\SerieController::class, 'index']);
Route::get('/genres', [\App\Http\Controllers\Api\GenreController::class, 'index']);
