<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Services\TmdbService;

class MovieController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService) {
        $this->tmdbService = $tmdbService;
    }

    public function index() {
        return response()->json(Movie::all(), 200);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year'  => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $movie = Movie::create($validated);

        return response()->json($movie, 201);
    }

    public function show($title) {
        $movie = Movie::find($title);
        if (!$movie) {
            return response()->json(['error' => 'Movie not found'], 404);
        }
        return response()->json($movie, 200);
    }

    public function update(Request $request, $id) {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['error' => 'Movie not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'year'  => 'sometimes|integer',
            'notes' => 'nullable|string',
        ]);

        $movie->update($validated);

        return response()->json($movie, 200);
    }

    public function destroy($id) {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['error' => 'Movie not found'], 404);
        }
        $movie->delete();

        return response()->json(null, 204);
    }

    public function search(Request $request, TmdbService $tmdb) {
        $query = $request->query('query');
        if (!$query) {
            return response()->json(['error' => 'Missing query parameter'], 400);
        }

        try {
            $results = $tmdb->searchMovies($query);
            return response()->json($results, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
