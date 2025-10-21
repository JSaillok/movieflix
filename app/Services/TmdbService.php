<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.themoviedb.org/3';
    protected ?array $config = null;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
    }

    public function request(string $endpoint, array $params = []): array
    {
        $params['api_key'] = $this->apiKey;

        $response = Http::get("{$this->baseUrl}/{$endpoint}", $params);

        if ($response->failed()) {
            throw new \Exception("TMDB API error: {$response->status()}", $response->status());
        }

        return $response->json();
    }

    public function buildImageUrl(string $filePath, string $size = 'w500'): string
    {
        $cfg = $this->config ??= $this->request('configuration');
        $base = $cfg['images']['secure_base_url'] ?? $cfg['images']['base_url'];

        return "{$base}{$size}{$filePath}";
    }
}
