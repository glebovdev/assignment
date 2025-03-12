<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private int $userId = 1;

    public function setUp(): void
    {
        parent::setUp();

        // Create a JWT token for testing
        $this->token = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwibmFtZSI6Ikhhcm1vbnkgRXJkbWFuIiwicm9sZXMiOlsicmF0ZXIiXX0.338D7ts-FMfMylLgZv3-VV8eY4GYxqb4SY0IVlVNi-w';

        // Create a test movie
        Movie::factory()->create([
            'id' => 1,
            'name' => 'Test Movie',
            'description' => 'A test movie description'
        ]);
    }

    public function test_user_can_create_rating_for_movie(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->put('/movie/1/rating', [
            'rating' => 4,
            'comment' => 'Great movie'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'ok',
                     'message' => 'Your rating has been saved'
                 ]);

        $this->assertDatabaseHas('ratings', [
            'user_id' => $this->userId,
            'movie_id' => 1,
            'rating' => 4,
            'comment' => 'Great movie'
        ]);
    }

    public function test_user_can_update_existing_rating(): void
    {
        // First, create a rating
        Rating::create([
            'user_id' => $this->userId,
            'movie_id' => 1,
            'rating' => 3,
            'comment' => 'Initial rating'
        ]);

        // Now update it
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->put('/movie/1/rating', [
            'rating' => 5,
            'comment' => 'Much better on second viewing'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'ok',
                     'message' => 'Your rating has been updated'
                 ]);

        $this->assertDatabaseHas('ratings', [
            'user_id' => $this->userId,
            'movie_id' => 1,
            'rating' => 5,
            'comment' => 'Much better on second viewing'
        ]);

        // Ensure only one rating exists for this user and movie
        $this->assertEquals(1,
            Rating::where('user_id', $this->userId)
                 ->where('movie_id', 1)
                 ->count()
        );
    }
}
