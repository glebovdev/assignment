<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Movie;
use App\Models\Rating;

class RatingController
{
    public function index(Movie $movie)
    {
        return RatingResource::collection($movie->ratings);
    }

    public function create(Movie $movie, StoreRatingRequest $request)
    {

        $rating = new Rating();
        $rating->fill([
            'user_id' => $request->user()->getAuthIdentifier(),
            'movie_id' => $movie->id,
        ]);
        $rating->fill($request->all());
        $rating->save();
        return 'ok';

    }
}
