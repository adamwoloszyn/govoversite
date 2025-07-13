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
        Schema::create('agenda_item_types', function (Blueprint $table) {
            $table->id();
            $table->string('short_description');
            $table->string('long_description');
            $table->text('template');
            $table->integer('order');
            $table->boolean('is_enabled')->default(true)->comment("Indicates if this template is available");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_item_types');
    }
};
