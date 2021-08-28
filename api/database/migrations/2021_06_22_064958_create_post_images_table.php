<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained();
            $table->unsignedTinyInteger('order');

            $table->string('original_filename');
            $table->string('title')->nullable();
            $table->string('recognized_text')->nullable();

            $table->string('original_file_path')->unique();
            $table->string('desktop_file_path')->nullable();
            $table->string('mobile_file_path')->nullable();

            $table->json('data');

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
        Schema::dropIfExists('post_images');
    }
}
