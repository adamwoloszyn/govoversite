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
        Schema::create('video_agenda_item_time_stamps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id');
            $table->text('comment');
            $table->time('video_jump_point')->default('00:00:00');
            $table->boolean('is_enabled')->default(true)->comment("Indicates if this template is available");
            $table->timestamps();

            // this is associated to a video associated
            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_agenda_item_time_stamps');
    }
};
