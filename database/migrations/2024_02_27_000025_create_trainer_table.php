<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainerTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainers', function (Blueprint $table) {
            //$table->id();
            $table->unsignedBigInteger('user_id')->primary(); // Define user_id as primary key
            $table->string('specialization');
            $table->text('description');
            $table->text('experience');
            $table->decimal('session_price', 10, 2);
            $table->string('profile_photo')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
}
