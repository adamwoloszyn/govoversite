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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default("");
            $table->string('slug')->default("")->comment("Slug used for SEO");
            $table->text('description');
            $table->text('agendaSummary');
            $table->unsignedBigInteger('video_category_id');
            $table->unsignedBigInteger('video_processing_state_id');
            $table->string('speakers')->default("");
            $table->string('videofilelocalpath')->default("")->comment("local location of uploaded video from admin");
            $table->string('aws_subdirectory')->default("");
            $table->string('videofileAWSpath')->default("")->comment("S3 name of video file");
            $table->string('compressedvideofileAWSpath')->default("")->comment("S3 name of compressed video file");
            $table->string('compressionjobid')->default("");
            $table->string('transcriptfilelocalpath')->comment("local path of file downloaded from Sonix");
            $table->string('transcriptfileAWSpath')->comment("S3 name of transcription file");
            $table->string('sonix_ai_media_id')->default("");
            $table->string('sound_cloud_url')->default("")->nullable(true);
            $table->string('thumbnail')->default("");
            $table->dateTime('when_was_video_created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('is_enabled')->default(true)->comment("Indicates if this video should be displayed");
            $table->longText('audit_log');
            $table->timestamps();

            // this has one category associated at a time
            $table->foreign('video_category_id')
                ->references('id')
                ->on('video_categories')
                ->noActionOnDelete();

            // this has one video processing state associated at a time
            $table->foreign('video_processing_state_id')
                ->references('id')
                ->on('video_processing_states')
                ->noActionOnDelete();
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
