<?php

use App\Models\Content\Post;
use App\Models\Rating\Rating;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ratingable_id');
            $table->string('ratingable_type');
            $table->unsignedInteger('positive_votes')->default(0);
            $table->unsignedInteger('negative_votes')->default(0);
            $table->timestamps();
            
            // Add indexes for faster querying
            $table->index(['ratingable_id', 'ratingable_type']);
        });
        
        foreach (User::all() as $user) {
            Rating::create([
                'ratingable_id' => $user->id,
                'ratingable_type' => User::class
            ]);
        }
        
        foreach (Post::all() as $post) {
            Rating::create([
                'ratingable_id' => $post->id,
                'ratingable_type' => Post::class
            ]);
        }
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
