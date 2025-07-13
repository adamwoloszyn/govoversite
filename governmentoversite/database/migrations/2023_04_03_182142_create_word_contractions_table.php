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
        Schema::create('word_contractions', function (Blueprint $table) {
            $table->id();
            $table->string('hash');//->unique();
            $table->string('contraction')->unique();
            $table->string('expansion')->default("");
            $table->boolean('isenabled')->default(true);
            $table->timestamps();

            // indexes
            $table->index('id');
            $table->index('hash');
            $table->index('contraction');
            $table->index('isenabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('word_contractions', function (Blueprint $table) {
            $table->dropIndex('word_contractions_id_index');
            $table->dropIndex('word_contractions_hash_index');
            $table->dropIndex('word_contractions_contraction_index');
            $table->dropIndex('word_contractions_isenabled_index');
        });

        Schema::dropIfExists('word_contractions');
    }
};
