<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->default("")->comment("Slug used for SEO");
            $table->string('channel_id');
            $table->text('description')->nullable();
            $table->text('pdf_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_enabled')->default(true)->comment("Indicates if this video should be displayed");
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
