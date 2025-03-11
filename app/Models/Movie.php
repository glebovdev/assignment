<?php

namespace App\Models;

use Database\Factories\MovieFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Movie extends Model
{
    /** @use HasFactory<MovieFactory> */
    use HasFactory;

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'movie_id', 'id');
    }
}
