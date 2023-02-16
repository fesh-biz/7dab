<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('ratingable_id');
            $table->string('ratingable_type');
            $table->boolean('is_positive');
            $table->timestamps();
    
            // Add indexes for faster querying
            $table->index(['user_id', 'ratingable_id', 'ratingable_type']);
            $table->index(['ratingable_id', 'ratingable_type', 'is_positive']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rating_votes');
    }
}
