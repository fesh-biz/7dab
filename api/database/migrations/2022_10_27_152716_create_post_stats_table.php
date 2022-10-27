<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_stats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_id')->unique()->constrained();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('positive_votes')->default(0);
            $table->unsignedInteger('negative_votes')->default(0);
            $table->unsignedInteger('comments')->default(0);

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
        Schema::dropIfExists('post_stats');
    }
}
