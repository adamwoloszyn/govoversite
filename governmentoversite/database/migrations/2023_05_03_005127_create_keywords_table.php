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
        Schema::create('keywords', function (Blueprint $table) {
            $table->id()->autoIncrement()->unique();
            $table->string('keyword');
            $table->boolean('is_enabled')->default(true)->comment("Indicates if this keyword is available");
            $table->timestamps();
        });
    }   // end of up()

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }   // end of down()
};
