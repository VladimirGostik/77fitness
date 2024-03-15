<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('room_id');
            $table->dateTime('start_reservation')->nullable(); // Add this line
            $table->dateTime('end_reservation')->nullable();   // Add this line
            $table->integer('max_participants');
            $table->boolean('is_reserved')->default(false);
            $table->timestamps();

            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
