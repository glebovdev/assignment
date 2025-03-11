<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MovieController
{
    public function index(): AnonymousResourceCollection
    {
        return MovieResource::collection(Movie::all());
    }

    public function get(Movie $movie): MovieResource
    {
        return new MovieResource($movie);
    }
}
