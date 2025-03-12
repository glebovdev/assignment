<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Movie;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

final class RatingController
{
    private const PER_PAGE = 15;

    public function index(Movie $movie, Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', self::PER_PAGE);
        return RatingResource::collection(
            $movie->ratings()->paginate($perPage)->withQueryString()
        );
    }

    public function create(Movie $movie, StoreRatingRequest $request): JsonResponse
    {
        $user_id = $request->user()->getAuthIdentifier();

        // Use updateOrCreate to either create a new rating or update existing one
        $rating = Rating::updateOrCreate(
            ['user_id' => $user_id, 'movie_id' => $movie->id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        $isNewRecord = $rating->wasRecentlyCreated;

        return response()->json([
            'status' => 'ok',
            'message' => $isNewRecord ? 'Your rating has been saved' : 'Your rating has been updated'
        ]);
    }
}
