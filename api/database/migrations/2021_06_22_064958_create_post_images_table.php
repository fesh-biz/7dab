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

            $table->string('original_name');
            $table->string('title')->nullable();
            $table->string('recognized_text')->nullable();

            $table->string('filename')->unique();
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->unsignedInteger('size_kb');

            $table->timestamps();
        });

        File::makeDirectory(config('7dab.post_original_images_folder'), 0777, true, true);
        File::makeDirectory(config('7dab.post_desktop_thumbnail_images_folder'), 0777, true, true);
        File::makeDirectory(config('7dab.post_mobile_thumbnail_images_folder'), 0777, true, true);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_images');

        if (File::exists(config('7dab.post_images_folder'))) {
            File::deleteDirectory(config('7dab.post_images_folder'));
        }
    }
}
