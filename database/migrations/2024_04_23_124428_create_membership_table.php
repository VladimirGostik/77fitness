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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('typ_permanentky_id');
            $table->date('platnost_od');
            $table->date('platnost_do')->nullable();
            $table->integer('pouzite_vstupy')->default(0);

            // Define foreign key constraints
            $table->foreign('client_id')->references('user_id')->on('clients')->onDelete('cascade');
            $table->foreign('typ_permanentky_id')->references('id')->on('typ_permanentky')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
