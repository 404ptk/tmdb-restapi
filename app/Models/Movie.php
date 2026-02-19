<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'tmdb_id',
        'title_en',
        'title_pl',
        'title_de',
        'overview_en',
        'overview_pl',
        'overview_de',
        'release_date',
        'poster_path',
        'popularity',
        'vote_average',
        'vote_count',
    ];

    protected $casts = [
        'release_date' => 'date',
        'popularity' => 'float',
        'vote_average' => 'float',
        'vote_count' => 'integer',
    ];
}
