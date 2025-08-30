<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService {
    protected $apiKey;
    protected $baseUrl = 'https://api.themoviedb.org/3';
    protected $config = null;

    public function __construct() {
        $this->apiKey = config('services.tmdb.api_key');
    }

    protected function request($endpoint, $params = []) {
        $params['api_key'] = $this->apiKey;
        $response = Http::get("{$this->baseUrl}/{$endpoint}", $params);
        if ($response->failed()) {
            throw new \Exception("TMDB API error: {$response->status()}");
        }
        return $response->json();
    }

    public function searchMovies(string $query): array
    {
        $data = $this->request('search/movie', [
            'query' => $query,
            'include_adult' => false,
            'language' => 'en-US',
            'page' => 1
        ]);

        // Map results to simplified structure
        return array_map(function ($movie) {
            return [
                'id' => $movie['id'],
                'title' => $movie['title'] ?? null,
                'year' => isset($movie['release_date']) ? substr($movie['release_date'], 0, 4) : null,
                'overview' => $movie['overview'] ?? null,
                'poster' => isset($movie['poster_path']) ? $this->buildImageUrl($movie['poster_path']) : null,
                'rating' => $movie['vote_average'] ?? null,
            ];
        }, $data['results'] ?? []);
    }

    public function getMovieDetails(int $id, ?string $append = null): array
    {
        $params = [];
        if ($append) {
            $params['append_to_response'] = $append;
        }

        return $this->request("movie/{$id}", $params);
    }

    protected function getConfiguration(): array
    {
        if (!$this->config) {
            $this->config = $this->request('configuration');
        }
        return $this->config;
    }

    public function buildImageUrl(string $filePath, string $size = 'w500'): string
    {
        $cfg = $this->getConfiguration();
        $base = $cfg['images']['secure_base_url'] ?? $cfg['images']['base_url'];

        return "{$base}{$size}{$filePath}";
    }
}