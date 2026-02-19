<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = [
        'tmdb_id',
        'name_en',
        'name_pl',
        'name_de',
    ];
}
