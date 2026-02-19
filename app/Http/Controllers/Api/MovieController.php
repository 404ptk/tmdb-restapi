<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->getPreferredLanguage(['en', 'pl', 'de']) ?? 'en';
        $perPage = (int) $request->query('per_page', 10);
        $query = Movie::query();

        $data = $query->paginate($perPage);

        $data->getCollection()->transform(function ($movie) use ($lang) {
            return [
                'id' => $movie->id,
                'tmdb_id' => $movie->tmdb_id,
                'title' => $movie->{'title_' . $lang} ?? $movie->title_en,
                'overview' => $movie->{'overview_' . $lang} ?? $movie->overview_en,
                'release_date' => $movie->release_date,
                'poster_path' => $movie->poster_path,
                'popularity' => $movie->popularity,
                'vote_average' => $movie->vote_average,
                'vote_count' => $movie->vote_count,
            ];
        });

        return response()->json($data);
    }
}
