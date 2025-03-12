<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class MovieController
{
    private const PER_PAGE = 15;

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', self::PER_PAGE);
        return MovieResource::collection(
            Movie::withAvg('ratings', 'rating')->paginate($perPage)->withQueryString()
        );
    }

    public function get(Movie $movie): MovieResource
    {
        $movie->loadAvg('ratings', 'rating');
        return new MovieResource($movie);
    }
}
