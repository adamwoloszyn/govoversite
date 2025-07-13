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
        Schema::create('video_keywords', function (Blueprint $table) {
            $table->id()->autoIncrement()->unique();
            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('keyword_id');
            $table->integer('lineNumber');
            $table->integer('position');
            $table->time('position_in_video')->default("00:00:00");

            $table->boolean('is_enabled')->default(true)->comment("Indicates if this is avaiable");

            $table->timestamps();

            // associated to video
            // $table->foreign('videos_id')
            //     ->references('id')
            //     ->on('videos')
            //     ->noActionOnDelete();

            // // associated to keywords
            // $table->foreign('keyword_id')
            //     ->references('id')
            //     ->on('keywords')
            //     ->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_keywords');
    }
};
