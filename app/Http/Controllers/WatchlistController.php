<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use App\Models\WatchlistItem;
use App\Services\TmdbService;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    // public function store(Request $request) {
    //     $request->validate(['name' => 'required|string']);
    //     Auth::user()->watchlists()->create($request->all());
    //     return redirect()->route('watchlists.index')->with('success', 'Watchlist created.');
    // }

    public function destroy(Watchlist $watchlist) {
        $watchlist->delete();
        return redirect()->route('watchlists.index')->with('success', 'Movie deleted.');
    }

    public function addItem(Request $request, Watchlist $watchlist) {
        $request->validate([
            'tmdb_id' => 'required_without:movie_id',
            'movie_id' => 'required_without:tmdb_id',
            'season' => 'nullable|integer',
            'episode' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $watchlist->items()->create($request->all());
        return redirect()->route('watchlists.index')->with('success', 'Item added to watchlist.');
    }

    public function updateItem(Request $request, WatchlistItem $item) {
        $request->validate(['notes' => 'nullable|string', 'season' => 'nullable|integer', 'episode' => 'nullable|integer']);
        $item->update($request->all());
        return redirect()->route('watchlists.index')->with('success', 'Item updated.');
    }

    public function destroyItem(WatchlistItem $item) {
        $item->delete();
        return redirect()->route('watchlists.index')->with('success', 'Item removed.');
    }
}
