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
        Schema::create('user_subscription', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('invoice')->default("Not Set")->comment("stripe invoice");
            $table->string('subscription')->default("Not Set")->comment("stripe subscription");

            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->boolean('is_enabled')->default(true)->comment("Indicates if this subscription is valid");
            $table->timestamps();

            
            // this is associated to a user
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->noActionOnDelete();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscription');
    }
};
