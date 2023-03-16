<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostYouTubesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_you_tubes', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('post_id')->constrained();
            $table->unsignedTinyInteger('order');
            
            $table->string('youtube_id');
            $table->string('title');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_you_tubes');
    }
}
