<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ratings', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies');
            $table->integer('user_id');
            $table->integer('rating')->index();
            $table->text('comment')->nullable();
            $table->timestamps();

            // Ensure each user can only rate a movie once
            $table->unique(['user_id', 'movie_id']);

            $table->index('movie_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
