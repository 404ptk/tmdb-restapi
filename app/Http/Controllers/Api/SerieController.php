<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->getPreferredLanguage(['en', 'pl', 'de']) ?? 'en';
        $perPage = (int) $request->query('per_page', 10);
        $query = Serie::query();

        $data = $query->paginate($perPage);

        $data->getCollection()->transform(function ($serie) use ($lang) {
            return [
                'id' => $serie->id,
                'tmdb_id' => $serie->tmdb_id,
                'title' => $serie->{'title_' . $lang} ?? $serie->title_en,
                'overview' => $serie->{'overview_' . $lang} ?? $serie->overview_en,
                'first_air_date' => $serie->first_air_date,
                'poster_path' => $serie->poster_path,
                'popularity' => $serie->popularity,
                'vote_average' => $serie->vote_average,
                'vote_count' => $serie->vote_count,
            ];
        });

        return response()->json($data);
    }
}
