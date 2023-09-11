<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('mediable_id')->nullable();
            $table->string('mediable_type')->nullable();

            $table->foreignId('user_id')->constrained('users');

            $table->unsignedTinyInteger('order')->nullable();
            $table->string('original_filename');
            $table->string('title')->nullable();
            $table->unsignedBigInteger('original_size')->nullable();
            $table->string('mime_type');
            $table->string('disc')->nullable();

            $table->json('data')->nullable();

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
        Schema::dropIfExists('media');
    }
}
