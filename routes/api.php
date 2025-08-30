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
Route::get('/movies', [MovieController::class, 'index']);       // GET all local movies
Route::post('/movies', [MovieController::class, 'store']);      // POST create local movie
Route::get('/movies/{id}', [MovieController::class, 'show']);   // GET single local movie
Route::put('/movies/{id}', [MovieController::class, 'update']); // PUT update local movie
Route::delete('/movies/{id}', [MovieController::class, 'destroy']); // DELETE local movie