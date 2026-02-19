<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->getPreferredLanguage(['en', 'pl', 'de']) ?? 'en';
        $query = Genre::query();
        $data = $query->orderBy('name_' . $lang)->get()->map(function ($genre) use ($lang) {
            return [
                'id' => $genre->id,
                'tmdb_id' => $genre->tmdb_id,
                'name' => $genre->{'name_' . $lang} ?? $genre->name_en,
            ];
        });
        return response()->json($data);
    }
}
