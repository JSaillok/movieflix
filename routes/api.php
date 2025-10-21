<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('movie', [MovieController::class, 'index']);

// TMDB search route
Route::get('/movies/search', [MovieController::class, 'search']); // GET external TMDB search

// CRUD routes for local movies
Route::resource('movies', MovieController::class);