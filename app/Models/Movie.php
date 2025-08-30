<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'year',
        'description',
        'poster_url',
        'tmdb_id',
        'is_series',
        'notes'
    ];

    protected $casts = [
        'year' => 'integer',
        'is_series' => 'boolean',
        'tmdb_id' => 'integer',
    ];

    public function watchlistItems() {
        return $this->hasMany(WatchlistItem::class);
    }
}
