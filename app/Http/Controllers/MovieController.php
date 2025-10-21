<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected TmdbService $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    // Local DB index
    public function index()
    {
        $movies = Movie::latest()->paginate(10);
        return view('movies.index', compact('movies'));
    }

    // Show movie details
    public function show($id)
    {
        // Try local DB first
        $movie = Movie::find($id);

        // If not in DB, fetch from TMDB
        if (!$movie) {
            $movie = $this->tmdb->request("movie/{$id}");
        }

        return view('movies.show', compact('movie'));
    }

    // Search (using DB first, fallback to TMDB)
    public function search(Request $request)
    {
        $term = $request->get('q');

        $movies = Movie::search($term)->get();

        if ($movies->isEmpty()) {
            $data = $this->tmdb->request('search/movie', [
                'query' => $term,
                'include_adult' => false,
                'language' => 'en-US',
                'page' => 1,
            ]);
            $movies = collect($data['results']);
        }

        return view('movies.search', compact('movies', 'term'));
    }
}