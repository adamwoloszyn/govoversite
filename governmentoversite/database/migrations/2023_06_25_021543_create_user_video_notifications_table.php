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
        Schema::create('user_video_notifications', function (Blueprint $table) {
            $table->id();

            // foreign links
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('video_id');

            // record if an email was sent out
            $table->boolean('was_email_sent_out')->default(false)->comment("Was an email notification sent out");

            // record if an email body was created
            $table->boolean('was_email_body_built')->default(false)->comment("Was an email body created");

            // target email address
            $table->string('email_address')->default("not set");

            // email body sent out
            $table->mediumText('email_body');

            // last update and creation date/time
            $table->timestamps();

            // this has one user
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->noActionOnDelete();

            // this has one video
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
        Schema::dropIfExists('user_video_notifications');
    }
};
