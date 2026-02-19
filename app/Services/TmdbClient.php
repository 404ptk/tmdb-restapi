<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class TmdbClient
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = (string) config('services.tmdb.key');
        $this->baseUrl = (string) config('services.tmdb.base_url', 'https://api.themoviedb.org/3');
    }

    public function isConfigured(): bool
    {
        return $this->apiKey !== '';
    }

    public function get(string $path, array $params = []): array
    {
        $response = $this->request()->get(ltrim($path, '/'), array_merge($params, [
            'api_key' => $this->apiKey,
        ]));

        return $response->throw()->json();
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)->timeout(15);
    }
}
