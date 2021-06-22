<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();

            $table->string('title');
            $table->integer('rating')->default(0);
            $table->string('slug')->unique();
            $table->boolean('is_approved')->default(false);
            $table->unsignedInteger('total_views')->default(0);
            $table->unsignedInteger('total_comments')->default(0);

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('posts');
        Schema::enableForeignKeyConstraints();
    }
}
