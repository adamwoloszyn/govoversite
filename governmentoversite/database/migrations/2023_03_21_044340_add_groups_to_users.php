<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Going to add users groups to user table
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1 = visitor  2 = subscriber  3=admin
            $table->string('customer')->default("Not Set")->comment("stripe customer");
            $table->string('role')->default(0);
            $table->string('checkout_session_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();
            //$table->dateTime('subscriptionDate')->nullable();
            //$table->dateTime('renewalDate')->nullable();
            $table->boolean('is_enabled')->default(true)->comment("Indicates if this user is valid");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
